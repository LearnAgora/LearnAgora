<?php

namespace La\CoreBundle\Model\Probability;

use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Profile;
use La\CoreBundle\Entity\UserProbability;

/**
 * @DI\Service
 */
class UserProbabilityCollection
{
    /**
     * @var array
     */
    private $profiles;

    /**
     * @var array
     */
    private $userProbabilities;

    /**
     *
     */
    public function __construct() {
        $this->profiles = array();
        $this->userProbabilities = array();
    }

    /**
     * @return array
     */
    public function getProfiles()
    {
        return $this->profiles;
    }

    /**
     * @param array $profiles
     */
    public function setProfiles(array $profiles)
    {
        foreach ($profiles as $profile) {
            /* @var Profile $profile */
            $this->profiles[$profile->getId()] = $profile;
        }
    }

    /**
     * @param array $userProbabilities
     */
    public function setUserProbabilities(array $userProbabilities)
    {
        foreach ($userProbabilities as $userProbability) {
            /* @var UserProbability $userProbability */
            $this->userProbabilities[$userProbability->getProfile()->getId()] = $userProbability;
        }
    }

    /**
     * @return array
     */
    public function getUserProbabilities()
    {
        return $this->userProbabilities;
    }

    /**
     * @param Profile $profile
     * @return UserProbability
     */
    public function getUserProbabilityForProfile(Profile $profile)
    {
        return isset($this->userProbabilities[$profile->getId()]) ? $this->userProbabilities[$profile->getId()] : null;
    }

    /**
     * @param Profile $profile
     * @param UserProbability $userProbability
     */
    public function setUserProbabilityForProfile(Profile $profile, UserProbability $userProbability)
    {
        $this->userProbabilities[$profile->getId()] = $userProbability;
    }

    /**
     * @return bool
     */
    public function hasMissingUserProbabilities()
    {
        $hasMissingUserProbabilities = false;
        foreach ($this->profiles as $key => $profile)
        {
            $hasMissingUserProbabilities = isset($this->userProbabilities[$key]) ? $hasMissingUserProbabilities : true;
        }
        return $hasMissingUserProbabilities;
    }

    /**
     * @return int
     */
    public function getDefaultUserProbabilityValue()
    {
        return 1;
    }

    /**
     *
     */
    public function normalizeUserProbabilities() {
        $totalProbability = 0;
        foreach ($this->userProbabilities as $userProbability) {
            /* @var UserProbability $userProbability */
            $totalProbability+= $userProbability->getProbability();
        }
        foreach ($this->userProbabilities as $userProbability) {
            /* @var UserProbability $userProbability */
            $userProbability->setProbability($userProbability->getProbability() / $totalProbability);
        }
    }


}
