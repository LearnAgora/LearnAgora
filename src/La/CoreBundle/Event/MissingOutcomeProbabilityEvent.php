<?php

namespace La\CoreBundle\Event;

use La\CoreBundle\Entity\Outcome;
use La\CoreBundle\Model\Probability\BayesData;
use Symfony\Component\EventDispatcher\Event;

class MissingOutcomeProbabilityEvent extends Event
{
    /**
     * @var Outcome
     */
    private $outcome;
    /**
     * @var BayesData
     */
    private $bayesData;
    /**
     * Constructor.
     *
     * @param Outcome $outcome
     * @param BayesData $bayesData
     */
    public function __construct(Outcome $outcome, BayesData $bayesData)
    {
        $this->outcome = $outcome;
        $this->bayesData = $bayesData;
    }

    /**
     * @return Outcome
     */
    public function getOutcome() {
        return $this->outcome;
    }

    /**
     * @return BayesData
     */
    public function getBayesData()
    {
        return $this->bayesData;
    }

}
