<?php

namespace La\LearnodexBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Affinity;
use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\PersonaMatch;
use La\CoreBundle\Entity\User;
use La\CoreBundle\Entity\Outcome;
use La\CoreBundle\Entity\Trace;
use La\CoreBundle\Entity\Uplink;
use La\CoreBundle\Model\ComparePersona;

/**
 * @DI\Service("la_learnodex.update_all_affinities")
 */
class UpdateAllAffinities
{
    /**
     * @var ObjectManager $entityManager
     */
    private $entityManager;
    /**
     * @var ObjectRepository
     */
    private $agoraRepository;

    /**
     * @var ObjectRepository
     */
    private $userRepository;

    /**
     * @var ObjectRepository
     */
    private $personaRepository;

    /**
     * @var ObjectRepository
     */
    private $personaMatchRepository;

    /**
     * @var ObjectRepository
     */
    private $affinityRepository;

    /**
     * Constructor.
     *
     * @param ObjectManager $entityManager
     * @param ObjectRepository $agoraRepository,
     * @param ObjectRepository $userRepository,
     * @param ObjectRepository $personaRepository,
     * @param ObjectRepository $personaMatchRepository,
     * @param ObjectRepository $affinityRepository
     *
     * @DI\InjectParams({
     *  "entityManager" = @DI\Inject("doctrine.orm.entity_manager"),
     *  "agoraRepository" = @DI\Inject("la_core.repository.agora"),
     *  "userRepository" = @DI\Inject("la_core.repository.user"),
     *  "personaRepository" = @DI\Inject("la_core.repository.persona"),
     *  "personaMatchRepository" = @DI\Inject("la_core.repository.persona_match"),
     *  "affinityRepository" = @DI\Inject("la_core.repository.affinity")
     * })
     */
    public function __construct(
        ObjectManager $entityManager,
        ObjectRepository $agoraRepository,
        ObjectRepository $userRepository,
        ObjectRepository $personaRepository,
        ObjectRepository $personaMatchRepository,
        ObjectRepository $affinityRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->agoraRepository = $agoraRepository;
        $this->userRepository = $userRepository;
        $this->personaRepository = $personaRepository;
        $this->personaMatchRepository = $personaMatchRepository;
        $this->affinityRepository = $affinityRepository;


        $agoraList = $this->agoraRepository->findAll();
        $userList = $this->userRepository->findAll();
        $personalities = $this->personaRepository->findAll();



        /** @var $agora Agora */
        foreach ($agoraList as $agora)
        {
            /** @var $user User */
            foreach ($userList as $user)
            {
                if ($user->isEnabled())
                {
                    $affinityForOutcome = 0;
                    $totalWeight = 0;
                    /** @var $downLink Uplink */
                    foreach ($agora->getDownlinks() as $downLink)
                    {
                        $child = $downLink->getChild();
                        $outcomes = $child->getOutcomes();
                        $weight = $child->getContent()->getDuration() * max($downLink->getWeight(),1);
                        $lastResult = 0;
                        $lastTimestamp = 0;
                        /** @var $outcome Outcome */
                        foreach ($outcomes as $outcome) {
                            $traces = $outcome->getTraces();
                            /** @var $trace Trace */
                            foreach ($traces as $trace) {
                                if ($trace->getUser()->getId() == $user->getId()) {
                                    $timestamp = strtotime($trace->getCreatedTime()->format('Y-m-d H:i:s'));
                                    if ($timestamp > $lastTimestamp) {
                                        $lastTimestamp = $timestamp;
                                        $lastResult = $outcome->getAffinity();
                                    }
                                }
                            }
                        }
                        $affinityForOutcome+= $weight*$lastResult;
                        $totalWeight+= $weight*100;
                    }

                    $affinityValue = $totalWeight ? 100*$affinityForOutcome/$totalWeight : 0;
                    $affinityValue = $affinityValue<0 ? 0 : $affinityValue;

                    $affinity = $this->affinityRepository->findOneBy(
                        array(
                            'user' => $user,
                            'agora' => $agora
                        )
                    );
                    if (!$affinity) {
                        $affinity = new Affinity();
                        $affinity->setUser($user);
                        $affinity->setAgora($agora);
                    }
                    $affinity->setValue($affinityValue);
                    $this->entityManager->persist($affinity);
                    $this->entityManager->flush();


                    $comparePersona = new ComparePersona();
                    foreach ($personalities as $personality) {
                        $difference = $comparePersona->compare($user,$personality->getUser());
                        $personaMatch = $this->personaMatchRepository->findOneBy(
                            array(
                                'user' => $user,
                                'persona' => $personality
                            )
                        );
                        if (!$personaMatch) {
                            $personaMatch = new PersonaMatch();
                            $personaMatch->setUser($user);
                            $personaMatch->setPersona($personality);
                        }
                        $personaMatch->setDifference($difference);
                        $this->entityManager->persist($personaMatch);
                    }
                    $this->entityManager->flush();
                }
            }
        }
    }

}
