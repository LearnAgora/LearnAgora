<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Visitor\VisitableInterface;
use La\CoreBundle\Visitor\VisitorInterface;
use La\LearnodexBundle\Model\Visitor\Goal\GetNameVisitor;

/**
 * Goal
 */
abstract class Goal implements VisitableInterface
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

    abstract public function accept(VisitorInterface $visitor);

    public function getName() {
        $getNameVisitor = new GetNameVisitor();
        return $this->accept($getNameVisitor);
    }
}
