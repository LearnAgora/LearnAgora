<?php

namespace La\CoreBundle;

final class Events
{
    /**
     * This event is dispatched each time a trace is recorded.
     *
     * @see La\CoreBundle\Event\TraceEvent
     *
     * @var string
     */
    const TRACE_CREATE = 'trace.created';
}
