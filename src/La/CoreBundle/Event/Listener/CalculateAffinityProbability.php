<?php

namespace La\CoreBundle\Event\Listener;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Affinity;
use La\CoreBundle\Entity\Repository\AffinityRepository;
use La\CoreBundle\Entity\Repository\OutcomeProbabilityRepository;
use La\CoreBundle\Entity\Repository\ProfileRepository;
use La\CoreBundle\Entity\Repository\UserProbabilityRepository;
use La\CoreBundle\Entity\Trace;
use La\CoreBundle\Entity\User;
use La\CoreBundle\Event\MissingOutcomeProbabilityEvent;
use La\CoreBundle\Event\MissingUserProbabilityEvent;
use La\CoreBundle\Event\TraceEvent;
use La\CoreBundle\Events;
use La\CoreBundle\Model\Probability\BayesTheorem;
use La\CoreBundle\Model\Probability\OutcomeProbabilityCollection;
use La\CoreBundle\Model\Probability\UserProbabilityCollection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @DI\Service
 */
class CalculateAffinityProbability
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
     * @DI\Observe(Events::TRACE_CREATED)
     *
     * @param TraceEvent $traceEvent
     */
    public function onResult(TraceEvent $traceEvent)
    {
        /* @var Trace $trace */
        $trace = $traceEvent->getTrace();
        /* @var User $user */
        $user = $trace->getUser();

        $outcome = $trace->getOutcome();
        $learningEntity = $outcome->getLearningEntity();
        $parents = $learningEntity->getUplinks();

        $profiles = $this->profileRepository->findAll();

        $outcomeProbabilities = $this->outcomeProbabilityRepository->findBy(array('outcome'=>$outcome));
        $this->outcomeProbabilityCollection->setProfiles($profiles);
        $this->outcomeProbabilityCollection->setOutcomeProbabilities($outcomeProbabilities);
        if ($this->outcomeProbabilityCollection->hasMissingUserProbabilities())  {
            $this->eventDispatcher->dispatch(Events::MISSING_OUTCOME_PROBABILITY, new MissingOutcomeProbabilityEvent($outcome, $this->outcomeProbabilityCollection));
        }

        $this->userProbabilityCollection->setProfiles($profiles);

        foreach ($parents as $parent) {
            $agora = $parent->getParent();

            $userProbabilities = $this->userProbabilityRepository->findBy(array('user'=>$user, 'learningEntity' => $agora));
            $this->userProbabilityCollection->setUserProbabilities($userProbabilities);

            if ($this->userProbabilityCollection->hasMissingUserProbabilities()) {
                $this->eventDispatcher->dispatch(Events::MISSING_USER_PROBABILITY, new MissingUserProbabilityEvent($user, $agora, $this->userProbabilityCollection));
            }

            $this->bayesTheorem->applyTo($this->userProbabilityCollection, $this->outcomeProbabilityCollection);

            $topUserProbability = $this->userProbabilityCollection->getTopUserProbability();
            /* @var Affinity $affinity */
            $affinity = $this->affinityRepository->findOneBy(array('user'=>$user,'agora'=>$agora));
            $affinity->setValue(100*$topUserProbability->getProbability());
            $affinity->setProfile($topUserProbability->getProfile());
            $this->entityManager->persist($affinity);

            $this->entityManager->flush();
        }

    }
}
