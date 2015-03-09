<?php

namespace La\CoreBundle\Event\Listener;

use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Event\TraceEvent;
use La\CoreBundle\Events;

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
