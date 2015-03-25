<?php

namespace La\CoreBundle\Event;

use La\CoreBundle\Entity\User;
use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Model\Probability\BayesData;
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
     * @var BayesData
     */
    private $bayesData;
    /**
     * Constructor.
     *
     * @param User $user
     * @param Agora $agora
     * @param BayesData $bayesData
     */
    public function __construct(User $user, Agora $agora, BayesData $bayesData)
    {
        $this->user = $user;
        $this->agora = $agora;
        $this->bayesData = $bayesData;
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
     * @return BayesData
     */
    public function getBayesData()
    {
        return $this->bayesData;
    }

}
