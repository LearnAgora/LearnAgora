<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 28.11.15
 * Time: 13:45
 */

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;

class Event {
    /**
     * @var integer
     *
     * @Serializer\Expose
     */
    private $id;
    /**
     * @var boolean
     *
     * @Serializer\Expose
     */
    private $seen = false;

    /**
     * @var boolean
     *
     * @Serializer\Expose
     */
    private $removed = false;

    /**
     * @Serializer\Expose
     */
    private $createdOn;

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
     * Set createdOn
     *
     * @param \DateTime $createdOn
     * @return UserProbabilityEvent
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    /**
     * Get createdOn
     *
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * Set seen
     *
     * @param boolean $seen
     * @return UserProbabilityEvent
     */
    public function setSeen($seen)
    {
        $this->seen = $seen;

        return $this;
    }

    /**
     * Get seen
     *
     * @return boolean
     */
    public function getSeen()
    {
        return $this->seen;
    }

    /**
     * Set removed
     *
     * @param boolean $removed
     * @return UserProbabilityEvent
     */
    public function setRemoved($removed)
    {
        $this->removed = $removed;

        return $this;
    }

    /**
     * Get removed
     *
     * @return boolean
     */
    public function getRemoved()
    {
        return $this->removed;
    }
} 