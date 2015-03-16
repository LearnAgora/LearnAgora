<?php

namespace La\CoreBundle\Event\Listener;

use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Trace;
use La\CoreBundle\Event\TraceEvent;
use La\CoreBundle\Events;

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
    public function __construct() {

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


        $outcomeProbabilities = $outcome->getProbabilities();

    }
}
