<?php

namespace La\LearnodexBundle\Event;

use La\CoreBundle\Entity\Trace;
use Symfony\Component\EventDispatcher\Event;

class TraceEvent extends Event
{
    /**
     * @var Trace
     */
    private $trace;

    /**
     * Constructor.
     *
     * @param Trace $trace
     */
    public function __construct(Trace $trace)
    {
        $this->trace = $trace;
    }

    /**
     * @return Trace
     */
    public function getTrace()
    {
        return $this->trace;
    }
}
