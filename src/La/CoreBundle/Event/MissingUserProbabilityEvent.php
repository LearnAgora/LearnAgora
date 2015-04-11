<?php

namespace La\CoreBundle\Event;

use La\CoreBundle\Entity\User;
use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Model\Probability\UserProbabilityCollection;
use Symfony\Component\EventDispatcher\Event;

class MissingUserProbabilityEvent extends Event
{
    /**
     * @var User
     */
    private $user;
    /**
     * @var Agora
     */
    private $agora;
    /**
     * @var UserProbabilityCollection
     */
    private $userProbabilityCollection;
    /**
     * Constructor.
     *
     * @param User $user
     * @param Agora $agora
     * @param UserProbabilityCollection $userProbabilityCollection
     */
    public function __construct(User $user, Agora $agora, UserProbabilityCollection $userProbabilityCollection)
    {
        $this->user = $user;
        $this->agora = $agora;
        $this->userProbabilityCollection = $userProbabilityCollection;
    }

    /**
     * @return User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @return Agora
     */
    public function getAgora() {
        return $this->agora;
    }

    /**
     * @return UserProbabilityCollection
     */
    public function getUserProbabilityCollection()
    {
        return $this->userProbabilityCollection;
    }

}
