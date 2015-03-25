<?php

namespace La\CoreBundle\Event\Listener;

use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Trace;
use La\CoreBundle\Entity\User;
use La\CoreBundle\Event\TraceEvent;
use La\CoreBundle\Events;
use La\CoreBundle\Model\Probability\UserProbabilities;

/**
 * @DI\Service
 */
class CalculateAffinityProbability
{
    /**
     * @var UserProbabilities
     */
    private $userProbabilities;

    /**
     * Constructor.
     *
     * @param UserProbabilities $userProbabilities

     *
     * @DI\InjectParams({
     *  "userProbabilities" = @DI\Inject("la.core_bundle.model.probability.user_probabilities")
     * })
     */
    public function __construct(UserProbabilities $userProbabilities) {
        $this->userProbabilities = $userProbabilities;
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
        foreach ($parents as $parent) {
            $agora = $parent->getParent();

            //load probabilities for this user
            $this->userProbabilities->setUser($user);
            $this->userProbabilities->setLearningEntity($agora);
            $this->userProbabilities->processOutcome($outcome);
        }

    }
}
