<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;

/**
 * @Serializer\ExclusionPolicy("all")
 *
 */
class Profile
{
    /**
     * @var integer
     *
     * @Serializer\Expose
     */
    private $id;

    /**
     * @var string
     *
     * @Serializer\Expose
     */
    private $name;

    /**
     * @var Collection
     */
    private $outcomeProbabilities;

    /**
     * @var Collection
     */
    private $userProbabilities;

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
     * @return Profile
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
     * Constructor
     */
    public function __construct()
    {
        $this->outcomeProbabilities = new ArrayCollection();
        $this->userProbabilities = new ArrayCollection();
    }

    /**
     * Add outcomeProbabilities
     *
     * @param OutcomeProbability $outcomeProbabilities
     * @return Profile
     */
    public function addOutcomeProbability(OutcomeProbability $outcomeProbabilities)
    {
        $this->outcomeProbabilities[] = $outcomeProbabilities;

        return $this;
    }

    /**
     * Remove outcomeProbabilities
     *
     * @param OutcomeProbability $outcomeProbabilities
     */
    public function removeOutcomeProbability(OutcomeProbability $outcomeProbabilities)
    {
        $this->outcomeProbabilities->removeElement($outcomeProbabilities);
    }

    /**
     * Get outcomeProbabilities
     *
     * @return Collection
     */
    public function getOutcomeProbabilities()
    {
        return $this->outcomeProbabilities;
    }

    /**
     * Add userProbabilities
     *
     * @param UserProbability $userProbabilities
     * @return Profile
     */
    public function addUserProbability(UserProbability $userProbabilities)
    {
        $this->userProbabilities[] = $userProbabilities;

        return $this;
    }

    /**
     * Remove userProbabilities
     *
     * @param UserProbability $userProbabilities
     */
    public function removeUserProbability(UserProbability $userProbabilities)
    {
        $this->userProbabilities->removeElement($userProbabilities);
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
