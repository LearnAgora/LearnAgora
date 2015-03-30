<?php

namespace La\CoreBundle\Model\Probability;

use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\OutcomeProbability;
use La\CoreBundle\Entity\Profile;

/**
 * @DI\Service
 */
class OutcomeProbabilityCollection
{

    /**
     * @var array
     */
    private $profiles;

    /**
     * @var array
     */
    private $outcomeProbabilities;

    public function __construct() {
        $this->profiles = array();
        $this->outcomeProbabilities = array();
    }

    public function getProfiles()
    {
        return $this->profiles;
    }
    public function setProfiles(array $profiles)
    {
        foreach ($profiles as $profile) {
            /* @var Profile $profile */
            $this->profiles[$profile->getId()] = $profile;
        }
    }

    /**
     * @return array
     */
    public function getOutcomeProbabilities()
    {
        return $this->outcomeProbabilities;
    }

    /**
     * @param Profile $profile
     * @return OutcomeProbability
     */
    public function getOutcomeProbabilityForProfile(Profile $profile)
    {
        return isset($this->outcomeProbabilities[$profile->getId()]) ? $this->outcomeProbabilities[$profile->getId()] : null;
    }

    /**
     * @param array $outcomeProbabilities
     */
    public function setOutcomeProbabilities(array $outcomeProbabilities)
    {
        foreach ($outcomeProbabilities as $outcomeProbability) {
            /* @var OutcomeProbability $outcomeProbability */
            $this->outcomeProbabilities[$outcomeProbability->getProfile()->getId()] = $outcomeProbability;
        }
    }

    public function setOutcomeProbabilityForProfile(Profile $profile, OutcomeProbability $outcomeProbability)
    {
        $this->outcomeProbabilities[$profile->getId()] = $outcomeProbability;
    }


    public function hasMissingUserProbabilities()
    {
        $hasMissingOutcomeProbabilities = false;
        foreach ($this->profiles as $key => $profile)
        {
            $hasMissingOutcomeProbabilities = isset($this->outcomeProbabilities[$key]) ? $hasMissingOutcomeProbabilities : true;
        }
        return $hasMissingOutcomeProbabilities;
    }

}
