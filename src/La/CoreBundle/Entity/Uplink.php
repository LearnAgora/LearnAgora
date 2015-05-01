<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;

/**
 * @Serializer\ExclusionPolicy("all")
 *
 */
class Uplink
{
    /**
     * @var integer
     *
     * @Serializer\Expose
     */
    private $id;

    private $parent;

    /**
     * @var LearningEntity
     *
     * @Serializer\Expose
     */
    private $child;

    /**
     * @var integer
     *
     * @Serializer\Expose
     */
    private $weight;


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
     * Set weight
     *
     * @param string $weight
     * @return Uplink
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return string
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set parent
     *
     * @param \La\CoreBundle\Entity\LearningEntity $parent
     * @return Uplink
     */
    public function setParent(\La\CoreBundle\Entity\LearningEntity $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \La\CoreBundle\Entity\LearningEntity
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set child
     *
     * @param \La\CoreBundle\Entity\LearningEntity $child
     * @return Uplink
     */
    public function setChild(\La\CoreBundle\Entity\LearningEntity $child = null)
    {
        $this->child = $child;

        return $this;
    }

    /**
     * Get child
     *
     * @return \La\CoreBundle\Entity\LearningEntity
     */
    public function getChild()
    {
        return $this->child;
    }
}
