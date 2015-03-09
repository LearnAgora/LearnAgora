<?php

namespace La\LearnodexBundle\Event\Listener;

use JMS\DiExtraBundle\Annotation as DI;
use La\LearnodexBundle\Event\TraceEvent;
use La\LearnodexBundle\Events;

/**
 * @DI\Service
 */
class OutcomeProcessor
{
    /**
     * @DI\Observe(Events::TRACE_CREATED)
     *
     * @param TraceEvent $traceEvent
     */
    public function onResult(TraceEvent $traceEvent)
    {
        //        $outcome->accept($this->processResultVisitor);
    }
}
