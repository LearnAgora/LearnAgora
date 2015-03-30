<?php

namespace La\CoreBundle\Model\Probability;

use La\CoreBundle\Entity\Profile;
use La\CoreBundle\Entity\Repository\ProfileRepository;
use La\CoreBundle\Entity\Repository\UserProbabilityRepository;
use La\CoreBundle\Entity\UserProbability;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

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
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * Constructor.
     *
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @DI\InjectParams({
     *  "eventDispatcher" = @DI\Inject("event_dispatcher")
     * })
     */
    public function __construct(EventDispatcherInterface $eventDispatcher) {
        $this->eventDispatcher = $eventDispatcher;
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

    public function hasMissingUserProbabilities()
    {
        $hasMissingUserProbabilities = false;
        foreach ($this->profiles as $key => $profile)
        {
            $hasMissingUserProbabilities = isset($this->userProbabilities[$key]) ? $hasMissingUserProbabilities : true;
        }
        return $hasMissingUserProbabilities;
    }
}
