<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Visitor\VisitableInterface;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * Result
 */
abstract class Result implements VisitableInterface
{
    /**
     * @var integer
     */
    private $id;
    /**
     * @var integer
     */
    private $value;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    abstract public function accept(VisitorInterface $visitor);

    /**
     * @var \La\CoreBundle\Entity\Outcome
     */
    private $outcome;


    /**
     * Set value
     *
     * @param integer $value
     * @return Result
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return integer
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set outcome
     *
     * @param \La\CoreBundle\Entity\Outcome $outcome
     * @return Result
     */
    public function setOutcome(\La\CoreBundle\Entity\Outcome $outcome = null)
    {
        $this->outcome = $outcome;

        return $this;
    }

    /**
     * Get outcome
     *
     * @return \La\CoreBundle\Entity\Outcome
     */
    public function getOutcome()
    {
        return $this->outcome;
    }
}
