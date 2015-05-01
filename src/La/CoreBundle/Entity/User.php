<?php

namespace La\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Entity\User as BaseUser;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;

/**
 * @Serializer\ExclusionPolicy("all")
 */
class User extends BaseUser
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var Collection
     */
    private $goals;

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
    private $progress;

    /**
     * @var
     */
    private $userProbabilities;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->learningEntities = new ArrayCollection();
        $this->traces = new ArrayCollection();
    }

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
     * Add progress
     *
     * @param Progress $progress
     * @return User
     */
    public function addProgress(Progress $progress)
    {
        $this->progress[] = $progress;

        return $this;
    }

    /**
     * Remove progress
     *
     * @param Progress $progress
     */
    public function removeProgress(Progress $progress)
    {
        $this->progress->removeElement($progress);
    }

    /**
     * Get progress
     *
     * @return Collection
     */
    public function getProgress()
    {
        return $this->progress;
    }



    /**
     * Add goals
     *
     * @param Goal $goals
     * @return User
     */
    public function addGoal(Goal $goals)
    {
        $this->goals[] = $goals;

        return $this;
    }

    /**
     * Remove goals
     *
     * @param Goal $goals
     */
    public function removeGoal(Goal $goals)
    {
        $this->goals->removeElement($goals);
    }

    /**
     * Get goals
     *
     * @return Collection
     */
    public function getGoals()
    {
        return $this->goals;
    }

    /**
     * Add userProbabilities
     *
     * @param UserProbability $userProbabilities
     * @return User
     */
    public function addUserProbability(UserProbability $userProbabilities)
    {
        $this->userProbabilities[] = $userProbabilities;

        return $this;
    }


    /**
     * Get userProbabilities
     *
     * @return Collection
     */
    public function getUserProbabilities()
    {
        return $this->userProbabilities;
    }
}
