<?php

namespace La\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Entity\User as BaseUser;

/**
 * User
 */
class User extends BaseUser
{
    /**
     * @var Collection
     */
    private $learningEntities;

    /**
     * @var Collection
     */
    private $traces;

    /**
     * @var Collection
     */
    private $affinities;
    /**
     * @var Collection
     */
    private $progress;

    /**
     * @var Collection
     */
    private $personas;
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->learningEntities = new ArrayCollection();
        $this->traces = new ArrayCollection();
        $this->affinities = new ArrayCollection();
    }

    /**
     * Add learning entity.
     *
     * @param LearningEntity $learningEntity
     *
     * @return User
     */
    public function addLearningEntity(LearningEntity $learningEntity)
    {
        $this->learningEntities[] = $learningEntity;

        return $this;
    }

    /**
     * Remove learning entity.
     *
     * @param LearningEntity $learningEntity
     */
    public function removeLearningEntity(LearningEntity $learningEntity)
    {
        $this->learningEntities->removeElement($learningEntity);
    }

    /**
     * Get learning entities.
     *
     * @return Collection
     */
    public function getLearningEntities()
    {
        return $this->learningEntities;
    }

    /**
     * Add trace.
     *
     * @param Trace
     *
     * @return User
     */
    public function addTrace(Trace $trace)
    {
        $this->traces[] = $trace;

        return $this;
    }

    /**
     * Remove trace.
     *
     * @param Trace $trace
     */
    public function removeTrace(Trace $trace)
    {
        $this->traces->removeElement($trace);
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
     * Add affinity.
     *
     * @param Affinity $affinity
     *
     * @return User
     */
    public function addAffinity(Affinity $affinity)
    {
        $this->affinities[] = $affinity;

        return $this;
    }

    /**
     * Remove affinity.
     *
     * @param Affinity $affinity
     */
    public function removeAffinity(Affinity $affinity)
    {
        $this->affinities->removeElement($affinity);
    }

    /**
     * Get affinities
     *
     * @return Collection
     */
    public function getAffinities()
    {
        return $this->affinities;
    }
    /**
     * @var integer
     */
    protected $id;


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
     * Add personas
     *
     * @param \La\CoreBundle\Entity\Persona $personas
     * @return User
     */
    public function addPersona(\La\CoreBundle\Entity\Persona $personas)
    {
        $this->personas[] = $personas;

        return $this;
    }

    /**
     * Remove personas
     *
     * @param \La\CoreBundle\Entity\Persona $personas
     */
    public function removePersona(\La\CoreBundle\Entity\Persona $personas)
    {
        $this->personas->removeElement($personas);
    }

    /**
     * Get personas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPersonas()
    {
        return $this->personas;
    }

    /**
     * Add progress
     *
     * @param \La\CoreBundle\Entity\Progress $progress
     * @return User
     */
    public function addProgress(\La\CoreBundle\Entity\Progress $progress)
    {
        $this->progress[] = $progress;

        return $this;
    }

    /**
     * Remove progress
     *
     * @param \La\CoreBundle\Entity\Progress $progress
     */
    public function removeProgress(\La\CoreBundle\Entity\Progress $progress)
    {
        $this->progress->removeElement($progress);
    }

    /**
     * Get progress
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProgress()
    {
        return $this->progress;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $goals;


    /**
     * Add goals
     *
     * @param \La\CoreBundle\Entity\Goal $goals
     * @return User
     */
    public function addGoal(\La\CoreBundle\Entity\Goal $goals)
    {
        $this->goals[] = $goals;

        return $this;
    }

    /**
     * Remove goals
     *
     * @param \La\CoreBundle\Entity\Goal $goals
     */
    public function removeGoal(\La\CoreBundle\Entity\Goal $goals)
    {
        $this->goals->removeElement($goals);
    }

    /**
     * Get goals
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGoals()
    {
        return $this->goals;
    }
}
