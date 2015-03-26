<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OutcomeProbability
 */
class OutcomeProbability
{
    /**
     * @var integer
     */
    private $id;
    /**
     * @var integer
     */
    private $probability;
    /**
     * @var Outcome
     */
    private $outcome;
    /**
     * @var Profile
     */
    private $profile;


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
     * Set probability
     *
     * @param string $probability
     * @return OutcomeProbability
     */
    public function setProbability($probability)
    {
        $this->probability = $probability;

        return $this;
    }

    /**
     * Get probability
     *
     * @return string 
     */
    public function getProbability()
    {
        return $this->probability;
    }

    /**
     * Set outcome
     *
     * @param Outcome $outcome
     * @return OutcomeProbability
     */
    public function setOutcome(Outcome $outcome = null)
    {
        $this->outcome = $outcome;

        return $this;
    }

    /**
     * Get outcome
     *
     * @return Outcome
     */
    public function getOutcome()
    {
        return $this->outcome;
    }


    /**
     * Set profile
     *
     * @param Profile $profile
     * @return OutcomeProbability
     */
    public function setProfile(Profile $profile = null)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile
     *
     * @return Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }
}
