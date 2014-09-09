<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Visitor\AffinityOutcomeVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * Outcome
 */
class AffinityOutcome extends Outcome
{
    /**
     * @var string
     */
    private $operator;

    /**
     * @var integer
     */
    private $treshold;


    /**
     * Set operator
     *
     * @param string $operator
     * @return AffinityOutcome
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * Get operator
     *
     * @return string 
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Set treshold
     *
     * @param string $treshold
     * @return AffinityOutcome
     */
    public function setTreshold($treshold)
    {
        $this->treshold = $treshold;

        return $this;
    }

    /**
     * Get treshold
     *
     * @return string 
     */
    public function getTreshold()
    {
        return $this->treshold;
    }

    public function accept(VisitorInterface $visitor) {
        if ($visitor instanceof AffinityOutcomeVisitorInterface) {
            return $visitor->visitAffinityOutcome($this);
        }

        return null;
    }

}
