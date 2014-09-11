<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * Outcome
 */
abstract class Outcome
{
    /**
     * @var integer
     */
    private $id;

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
     * @var \La\CoreBundle\Entity\LearningEntity
     */
    private $learningEntity;


    /**
     * Set learningEntity
     *
     * @param \La\CoreBundle\Entity\LearningEntity $learningEntity
     * @return Outcome
     */
    public function setLearningEntity(\La\CoreBundle\Entity\LearningEntity $learningEntity = null)
    {
        $this->learningEntity = $learningEntity;

        return $this;
    }

    /**
     * Get learningEntity
     *
     * @return \La\CoreBundle\Entity\LearningEntity 
     */
    public function getLearningEntity()
    {
        return $this->learningEntity;
    }

    abstract function accept(VisitorInterface $visitor);


    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $results;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->results = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add results
     *
     * @param \La\CoreBundle\Entity\Result $results
     * @return Outcome
     */
    public function addResult(\La\CoreBundle\Entity\Result $results)
    {
        $this->results[] = $results;

        return $this;
    }

    /**
     * Remove results
     *
     * @param \La\CoreBundle\Entity\Result $results
     */
    public function removeResult(\La\CoreBundle\Entity\Result $results)
    {
        $this->results->removeElement($results);
    }

    /**
     * Get results
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getResults()
    {
        return $this->results;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $traces;


    /**
     * Add traces
     *
     * @param \La\CoreBundle\Entity\Trace $traces
     * @return Outcome
     */
    public function addTrace(\La\CoreBundle\Entity\Trace $traces)
    {
        $this->traces[] = $traces;

        return $this;
    }

    /**
     * Remove traces
     *
     * @param \La\CoreBundle\Entity\Trace $traces
     */
    public function removeTrace(\La\CoreBundle\Entity\Trace $traces)
    {
        $this->traces->removeElement($traces);
    }

    /**
     * Get traces
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTraces()
    {
        return $this->traces;
    }
}
