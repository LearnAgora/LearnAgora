<?php

namespace La\CoreBundle\Event\Listener;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Repository\OutcomeProbabilityRepository;
use La\CoreBundle\Entity\Repository\ProfileRepository;
use La\CoreBundle\Entity\Repository\UserProbabilityRepository;
use La\CoreBundle\Entity\Trace;
use La\CoreBundle\Entity\User;
use La\CoreBundle\Event\MissingOutcomeProbabilityEvent;
use La\CoreBundle\Event\MissingUserProbabilityEvent;
use La\CoreBundle\Event\TraceEvent;
use La\CoreBundle\Event\UserProbabilityUpdatedEvent;
use La\CoreBundle\Events;
use La\CoreBundle\Model\Probability\BayesTheorem;
use La\CoreBundle\Model\Probability\OutcomeProbabilityCollection;
use La\CoreBundle\Model\Probability\UserProbabilityCollection;
use La\CoreBundle\Model\Probability\UserProbabilityTrigger;
use La\CoreBundle\Model\Trace\UserTraceTrigger;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @DI\Service
 */
class CalculateAgoraProbability
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
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var UserProbabilityTrigger
     */
    private $userProbabilityTrigger;

    /**
     * @var UserTraceTrigger
     */
    private $userTraceTrigger;

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
     * @param EventDispatcherInterface $eventDispatcher
     * @param UserProbabilityTrigger $userProbabilityTrigger
     * @param UserTraceTrigger $userTraceTrigger
     *
     * @DI\InjectParams({
     *  "entityManager" = @DI\Inject("doctrine.orm.entity_manager"),
     *  "userProbabilityCollection" = @DI\Inject("la.core_bundle.model.probability.user_probability_collection"),
     *  "outcomeProbabilityCollection" = @DI\Inject("la.core_bundle.model.probability.outcome_probability_collection"),
     *  "profileRepository" = @DI\Inject("la_core.repository.profile"),
     *  "userProbabilityRepository" = @DI\Inject("la_core.repository.user_probability"),
     *  "outcomeProbabilityRepository" = @DI\Inject("la_core.repository.outcome_probability"),
     *  "bayesTheorem" = @DI\Inject("la.core_bundle.model.probability.bayes_theorem"),
     *  "eventDispatcher" = @DI\Inject("event_dispatcher"),
     *  "userProbabilityTrigger" = @DI\Inject("la.core_bundle.model.probability.user_probability_trigger"),
     *  "userTraceTrigger" = @DI\Inject("la.core_bundle.model.trace.user_trace_trigger")
     * })
     */
    public function __construct(
        ObjectManager $entityManager,
        UserProbabilityCollection $userProbabilityCollection,
        OutcomeProbabilityCollection $outcomeProbabilityCollection,
        ProfileRepository $profileRepository,
        UserProbabilityRepository $userProbabilityRepository,
        OutcomeProbabilityRepository $outcomeProbabilityRepository,
        BayesTheorem $bayesTheorem,
        EventDispatcherInterface $eventDispatcher,
        UserProbabilityTrigger $userProbabilityTrigger,
        UserTraceTrigger $userTraceTrigger)
    {
        $this->entityManager = $entityManager;
        $this->userProbabilityCollection = $userProbabilityCollection;
        $this->outcomeProbabilityCollection = $outcomeProbabilityCollection;
        $this->profileRepository = $profileRepository;
        $this->userProbabilityRepository = $userProbabilityRepository;
        $this->outcomeProbabilityRepository = $outcomeProbabilityRepository;
        $this->bayesTheorem = $bayesTheorem;
        $this->eventDispatcher = $eventDispatcher;
        $this->userProbabilityTrigger = $userProbabilityTrigger;
        $this->userTraceTrigger = $userTraceTrigger;
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

            /*
            $events = $this->userProbabilityTrigger->getEvents($userProbabilities);
            foreach ($events as $event) {
                $this->entityManager->persist($event);
                $user->addEvent($event);
            }
            */
            $this->entityManager->flush();

            $this->eventDispatcher->dispatch(Events::USER_PROBABILITY_UPDATED, new UserProbabilityUpdatedEvent($user,$agora));
        }

        /*
        $events = $this->userTraceTrigger->getEvents($user);
        foreach ($events as $event) {
            $this->entityManager->persist($event);
            $user->addEvent($event);
        }
        */
        $this->entityManager->flush();
    }
}
