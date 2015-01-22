<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Visitor\VisitableInterface;
use La\CoreBundle\Model\Goal\Goal as GoalBase;

/**
 * Goal
 */
abstract class Goal extends GoalBase implements VisitableInterface
{
    /**
     * @var integer
     */
    private $id;

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
     * @var User
     */
    private $user;


    /**
     * Set user
     *
     * @param User $user
     * @return Goal
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

}
