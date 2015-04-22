<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;
use La\CoreBundle\Visitor\VisitableInterface;

/**
 * @Serializer\ExclusionPolicy("all")
 *
 */
abstract class Goal implements VisitableInterface
{
    /**
     * @var integer
     *
     * @Serializer\Expose
     */
    private $id;

    /**
     * @var User
     */
    private $user;

    /**
     * @var Boolean
     *
     * @Serializer\Expose
     */
    private $active = false;

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
     * Set user
     *
     * @param User $user
     *
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

    /**
     * Set active
     *
     * @param boolean $active
     * @return Goal
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @return string
     */
    abstract public function getName();
}
