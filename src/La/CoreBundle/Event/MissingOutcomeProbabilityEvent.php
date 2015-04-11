<?php

namespace La\CoreBundle\Event;

use La\CoreBundle\Entity\Outcome;
use La\CoreBundle\Model\Probability\OutcomeProbabilityCollection;
use Symfony\Component\EventDispatcher\Event;

class MissingOutcomeProbabilityEvent extends Event
{
    /**
     * @var Outcome
     */
    private $outcome;
    /**
     * @var OutcomeProbabilityCollection
     */
    private $outcomeProbabilityCollection;
    /**
     * Constructor.
     *
     * @param Outcome $outcome
     * @param OutcomeProbabilityCollection $outcomeProbabilityCollection
     */
    public function __construct(Outcome $outcome, OutcomeProbabilityCollection $outcomeProbabilityCollection)
    {
        $this->outcome = $outcome;
        $this->outcomeProbabilityCollection = $outcomeProbabilityCollection;
    }

    /**
     * @return Outcome
     */
    public function getOutcome() {
        return $this->outcome;
    }

    /**
     * @return OutcomeProbabilityCollection
     */
    public function getOutcomeProbabilityCollection()
    {
        return $this->outcomeProbabilityCollection;
    }

}
