<?php

namespace La\CoreBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * @Serializer\ExclusionPolicy("all")
 */
abstract class Outcome
{
    /**
     * @var integer
     *
     * @Serializer\Expose
     */
    private $id;

    /**
     * @var integer
     *
     * @Serializer\Expose
     */
    private $affinity;

    /**
     * @var integer
     *
     * @Serializer\Expose
     */
    private $progress;

    /**
     * @var Collection
     *
     */
    private $probabilities;

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
     * @var LearningEntity
     */
    private $learningEntity;


    /**
     * Set learningEntity
     *
     * @param LearningEntity $learningEntity
     * @return Outcome
     */
    public function setLearningEntity(LearningEntity $learningEntity = null)
    {
        $this->learningEntity = $learningEntity;

        return $this;
    }

    /**
     * Get learningEntity
     *
     * @return LearningEntity
     */
    public function getLearningEntity()
    {
        return $this->learningEntity;
    }

    abstract public function accept(VisitorInterface $visitor);


    /**
     * @var Collection
     */
    private $results;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->results = new ArrayCollection();
    }

    /**
     * @var Collection
     */
    private $traces;


    /**
     * Add traces
     *
     * @param Trace $traces
     * @return Outcome
     */
    public function addTrace(Trace $traces)
    {
        $this->traces[] = $traces;

        return $this;
    }

    /**
     * Remove traces
     *
     * @param Trace $traces
     */
    public function removeTrace(Trace $traces)
    {
        $this->traces->removeElement($traces);
    }

    /**
     * Get traces
     *
     * @return Collection
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

    /**
     * Add probabilities
     *
     * @param OutcomeProbability $probabilities
     * @return Outcome
     */
    public function addProbability(OutcomeProbability $probabilities)
    {
        $this->probabilities[] = $probabilities;

        return $this;
    }

    /**
     * Remove probabilities
     *
     * @param OutcomeProbability $probabilities
     */
    public function removeProbability(OutcomeProbability $probabilities)
    {
        $this->probabilities->removeElement($probabilities);
    }

    /**
     * Get probabilities
     *
     * @return Collection
     */
    public function getProbabilities()
    {
        return $this->probabilities;
    }
}
