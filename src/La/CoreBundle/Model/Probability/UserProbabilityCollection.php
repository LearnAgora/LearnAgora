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

    public function __construct() {
        $this->profiles = array();
        $this->userProbabilities = array();
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

    public function setUserProbabilities(array $userProbabilities)
    {
        foreach ($userProbabilities as $userProbability) {
            /* @var UserProbability $userProbability */
            $this->userProbabilities[$userProbability->getProfile()->getId()] = $userProbability;
        }
    }

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

    public function setUserProbabilityForProfile(Profile $profile, UserProbability $userProbability)
    {
        $this->userProbabilities[$profile->getId()] = $userProbability;
    }

    public function hasMissingUserProbabilities()
    {
        $hasMissingUserProbabilities = false;
        foreach ($this->profiles as $key => $profile)
        {
            $hasMissingUserProbabilities = isset($this->userProbabilities[$key]) ? $hasMissingUserProbabilities : true;
        }
        return $hasMissingUserProbabilities;
    }

    public function getDefaultUserProbabilityValue()
    {
        return 1;
    }

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

    public function getTopUserProbability() {
        $topValue = 0;
        /* @var UserProbability $topUserProbability */
        $topUserProbability = null;
        foreach ($this->userProbabilities as $userProbability) {
            /* @var UserProbability $userProbability */
            if ($userProbability->getProbability() > $topValue) {
                $topValue = $userProbability->getProbability();
                $topUserProbability = $userProbability;
            }
        }
        return $topUserProbability;
    }


}
