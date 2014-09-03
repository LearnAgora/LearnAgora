<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Model\LearningEntityVisitorInterface;

/**
 * Particle
 */
abstract class LearningEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name = "";

    /**
     * @var string
     */
    private $description = "";

    /**
     * @var integer
     */
    private $owner;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Particle
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Particle
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    abstract function accept(LearningEntityVisitorInterface $visitor);

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $outcomes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->outcomes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add outcomes
     *
     * @param \La\CoreBundle\Entity\Outcome $outcomes
     * @return LearningEntity
     */
    public function addOutcome(\La\CoreBundle\Entity\Outcome $outcomes)
    {
        $this->outcomes[] = $outcomes;

        return $this;
    }

    /**
     * Remove outcomes
     *
     * @param \La\CoreBundle\Entity\Outcome $outcomes
     */
    public function removeOutcome(\La\CoreBundle\Entity\Outcome $outcomes)
    {
        $this->outcomes->removeElement($outcomes);
    }

    /**
     * Get outcomes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOutcomes()
    {
        return $this->outcomes;
    }

    /**
     * Set owner
     *
     * @param \La\CoreBundle\Entity\User $owner
     * @return LearningEntity
     */
    public function setOwner(\La\CoreBundle\Entity\User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \La\CoreBundle\Entity\User 
     */
    public function getOwner()
    {
        return $this->owner;
    }
}
