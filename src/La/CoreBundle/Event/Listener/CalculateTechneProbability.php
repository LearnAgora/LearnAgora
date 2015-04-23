<?php

namespace La\CoreBundle\Event\Listener;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Affinity;
use La\CoreBundle\Entity\Profile;
use La\CoreBundle\Entity\Repository\AffinityRepository;
use La\CoreBundle\Entity\Repository\OutcomeProbabilityRepository;
use La\CoreBundle\Entity\Repository\ProfileRepository;
use La\CoreBundle\Entity\Repository\UserProbabilityRepository;
use La\CoreBundle\Entity\Uplink;
use La\CoreBundle\Event\AffinityUpdatedEvent;
use La\CoreBundle\Events;
use La\CoreBundle\Model\Probability\BayesTheorem;
use La\CoreBundle\Model\Probability\OutcomeProbabilityCollection;
use La\CoreBundle\Model\Probability\UserProbabilityCollection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @DI\Service
 */
class CalculateTechneProbability
{
    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * @var UserProbabilityCollection
     */
    private $userProbabilityCollection;

    /**
     * @var OutcomeProbabilityCollection
     */
    private $outcomeProbabilityCollection;

    /**
     * @var ProfileRepository
     */
    private $profileRepository;

    /**
     * @var UserProbabilityRepository
     */
    private $userProbabilityRepository;

    /**
     * @var OutcomeProbabilityRepository
     */
    private $outcomeProbabilityRepository;

    /**
     * @var BayesTheorem
     */
    private $bayesTheorem;

    /**
     * @var AffinityRepository
     */
    private $affinityRepository;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * Constructor.
     *
     * @param ObjectManager $entityManager
     * @param UserProbabilityCollection $userProbabilityCollection
     * @param OutcomeProbabilityCollection $outcomeProbabilityCollection
     * @param ProfileRepository $profileRepository
     * @param UserProbabilityRepository $userProbabilityRepository
     * @param OutcomeProbabilityRepository $outcomeProbabilityRepository
     * @param BayesTheorem $bayesTheorem
     * @param AffinityRepository $affinityRepository
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @DI\InjectParams({
     *  "entityManager" = @DI\Inject("doctrine.orm.entity_manager"),
     *  "userProbabilityCollection" = @DI\Inject("la.core_bundle.model.probability.user_probability_collection"),
     *  "outcomeProbabilityCollection" = @DI\Inject("la.core_bundle.model.probability.outcome_probability_collection"),
     *  "profileRepository" = @DI\Inject("la_core.repository.profile"),
     *  "userProbabilityRepository" = @DI\Inject("la_core.repository.user_probability"),
     *  "outcomeProbabilityRepository" = @DI\Inject("la_core.repository.outcome_probability"),
     *  "bayesTheorem" = @DI\Inject("la.core_bundle.model.probability.bayes_theorem"),
     *  "affinityRepository" = @DI\Inject("la_core.repository.affinity"),
     *  "eventDispatcher" = @DI\Inject("event_dispatcher")
     * })
     */
    public function __construct(ObjectManager $entityManager, UserProbabilityCollection $userProbabilityCollection, OutcomeProbabilityCollection $outcomeProbabilityCollection, ProfileRepository $profileRepository, UserProbabilityRepository $userProbabilityRepository, OutcomeProbabilityRepository $outcomeProbabilityRepository, BayesTheorem $bayesTheorem, AffinityRepository $affinityRepository, EventDispatcherInterface $eventDispatcher)
    {
        $this->entityManager = $entityManager;
        $this->userProbabilityCollection = $userProbabilityCollection;
        $this->outcomeProbabilityCollection = $outcomeProbabilityCollection;
        $this->profileRepository = $profileRepository;
        $this->userProbabilityRepository = $userProbabilityRepository;
        $this->outcomeProbabilityRepository = $outcomeProbabilityRepository;
        $this->bayesTheorem = $bayesTheorem;
        $this->affinityRepository = $affinityRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @DI\Observe(Events::AFFINITY_UPDATED)
     *
     * @param AffinityUpdatedEvent $affinityUpdatedEvent
     */
    public function onResult(AffinityUpdatedEvent $affinityUpdatedEvent)
    {
        $affinity = $affinityUpdatedEvent->getAffinity();
        $user = $affinity->getUser();
        $agora = $affinity->getAgora();

        /* @var Profile $fluentProfile */
        $fluentProfile = $this->profileRepository->find(1);

        $parentLinks = $agora->getUplinks();

        foreach ($parentLinks as $parentLink) {
            /* @var Uplink $parentLink */
            $techne = $parentLink->getParent();
            $childrenLinks = $techne->getDownlinks();

            $affinityValue = 0;
            $weight = 0;
            foreach ($childrenLinks as $childrenLink) {
                /* @var Uplink $childrenLink */
                $child = $childrenLink->getChild();
                $weight+= floatval($childrenLink->getWeight());

                /* @var Affinity $affinity */
                $affinity = $this->affinityRepository->findOneBy(array('user'=>$user,'agora'=>$child));
                if ($affinity && $affinity->getProfile()->getName()=="Fluent" && $affinity->getValue()>90) {
                    $affinityValue+= floatval($childrenLink->getWeight());
                }
            }

            $affinityValue = $weight>0 ? 100*$affinityValue/$weight : 0;

            /* @var Affinity $affinity */
            $affinity = $this->affinityRepository->findOneBy(array('user'=>$user,'agora'=>$techne));
            if (!$affinity) {
                $affinity = new Affinity();
                $affinity->setUser($user);
                $affinity->setAgora($techne);
            }
            $affinity->setValue($affinityValue);
            $affinity->setProfile($fluentProfile);
            $this->entityManager->persist($affinity);

            $this->entityManager->flush();
        }

    }
}
