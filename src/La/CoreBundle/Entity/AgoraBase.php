<?php

namespace La\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;


/**
 * @Serializer\ExclusionPolicy("all")
 *
 */

abstract class AgoraBase extends LearningEntity
{
    /**
     * @var Collection
     */
    private $affinities;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->affinities = new ArrayCollection();
    }

    /**
     * Add affinities
     *
     * @param Affinity $affinities
     * @return Agora
     */
    public function addAffinity(Affinity $affinities)
    {
        $this->affinities[] = $affinities;

        return $this;
    }

    /**
     * Remove affinities
     *
     * @param Affinity $affinities
     */
    public function removeAffinity(Affinity $affinities)
    {
        $this->affinities->removeElement($affinities);
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
     * @var Collection
     */
    private $goals;


    /**
     * Add goals
     *
     * @param Goal $goals
     * @return Agora
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
}
