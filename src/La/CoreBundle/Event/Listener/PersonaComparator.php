<?php

namespace La\CoreBundle\Event\Listener;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Persona;
use La\CoreBundle\Entity\PersonaMatch;
use La\CoreBundle\Event\TraceEvent;
use La\CoreBundle\Events;
use La\CoreBundle\Model\ComparePersona;

/**
 * @DI\Service
 */
class PersonaComparator
{
    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * @var ObjectRepository
     *
     */
    private $personaRepository;

    /**
     * @var ObjectRepository
     *
     */
    private $personaMatchRepository;

    /**
     * Constructor.
     *
     * @param ObjectManager $entityManager
     * @param ObjectRepository $personaRepository
     * @param ObjectRepository $personaMatchRepository
     *
     * @DI\InjectParams({
     *  "entityManager" = @DI\Inject("doctrine.orm.entity_manager"),
     *  "personaRepository" = @DI\Inject("la_core.repository.persona"),
     *  "personaMatchRepository" = @DI\Inject("la_core.repository.persona_match")
     * })
     */
    public function __construct(ObjectManager $entityManager, ObjectRepository $personaRepository, ObjectRepository $personaMatchRepository)
    {
        $this->entityManager = $entityManager;
        $this->personaRepository = $personaRepository;
        $this->personaMatchRepository = $personaMatchRepository;
    }


    /**
     * @DI\Observe(Events::TRACE_CREATED)
     *
     * @param TraceEvent $traceEvent
     */
    public function onResult(TraceEvent $traceEvent)
    {
        $trace = $traceEvent->getTrace();
        $user = $trace->getUser();

        $personalities = $this->personaRepository->findAll();

        $comparePersona = new ComparePersona();

        foreach ($personalities as $personality) {
            /* @var Persona $personality */
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
