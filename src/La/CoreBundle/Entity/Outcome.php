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
     * @var integer
     */
    private $affinity;

    /**
     * @var integer
     */
    private $progress;

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

    abstract public function accept(VisitorInterface $visitor);


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

    /**
     * Set affinity
     *
     * @param integer $affinity
     * @return Outcome
     */
    public function setAffinity($affinity)
    {
        $this->affinity = $affinity;

        return $this;
    }

    /**
     * Get affinity
     *
     * @return integer 
     */
    public function getAffinity()
    {
        return $this->affinity;
    }

    /**
     * Set progress
     *
     * @param integer $progress
     * @return Outcome
     */
    public function setProgress($progress)
    {
        $this->progress = $progress;

        return $this;
    }

    /**
     * Get progress
     *
     * @return integer 
     */
    public function getProgress()
    {
        return $this->progress;
    }
}
