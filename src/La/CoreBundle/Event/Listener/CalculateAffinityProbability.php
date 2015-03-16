<?php

namespace La\CoreBundle\Event\Listener;

use Doctrine\Common\Persistence\ObjectRepository;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Event\TraceEvent;

/**
 * @DI\Service
 */
class CalculateAffinityProbability
{

    /**
     * @var ObjectRepository
     */
    private $userProbabilityRepository;

    /**
     * Constructor.
     *
     * @param ObjectRepository $userProbabilityRepository

     *
     * @DI\InjectParams({
     *  "userProbabilityRepository" = @DI\Inject("la_core.repository.userProbability")
     * })
     */
    public function __construct(ObjectRepository $userProbabilityRepository) {
        $this->userProbabilityRepository = $userProbabilityRepository;
    }

    /**
     * @DI\Observe(Events::TRACE_CREATED)
     *
     * @param TraceEvent $traceEvent
     */
    public function onResult(TraceEvent $traceEvent)
    {
        //        $this->compareWithPersona($user);
        /* Trace $trace */
        $trace = $traceEvent->getTrace();
        $outcome = $trace->getOutcome();
        $learningEntity = $outcome->getLearningEntity();

        //load probabilities for this user
        $userProbabilities = $this->userProbabilityRepository->findFor($trace->getUser(), $learningEntity);

        $outcomeProbabilities = $outcome->getProbabilities();

    }
}
