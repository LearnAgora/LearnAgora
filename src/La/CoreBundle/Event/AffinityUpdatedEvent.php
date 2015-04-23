<?php

namespace La\CoreBundle\Event;

use La\CoreBundle\Entity\Affinity;
use Symfony\Component\EventDispatcher\Event;

class AffinityUpdatedEvent extends Event
{
    /**
     * @var Affinity
     */
    private $affinity;

    /**
     * Constructor.
     *
     * @param Affinity $affinity
     */
    public function __construct(Affinity $affinity)
    {
        $this->affinity = $affinity;
    }

    /**
     * @return Affinity
     */
    public function getAffinity()
    {
        return $this->affinity;
    }
}
