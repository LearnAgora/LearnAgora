<?php

namespace La\CoreBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use La\CoreBundle\Visitor\VisitableInterface;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * @Serializer\ExclusionPolicy("all")
 */
abstract class LearningEntity implements VisitableInterface
{
    /**
     * @var integer
     *
     * @Serializer\Expose
     */
    private $id;

    /**
     * @var string
     *
     * @Serializer\Expose
     */
    private $name = "";

    /**
     * @var integer
     */
    private $owner;

    /**
     * @var Collection
     */
    private $outcomes;

    /**
     * @var Collection
     */
    private $uplinks;

    /**
     * @var Collection
     */
    private $downlinks;

    /**
     * @var Content
     */
    private $content;

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
     * Set name
     *
     * @param string $name
     * @return Particle
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    abstract function accept(VisitorInterface $visitor);

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->outcomes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add outcomes
     *
     * @param \La\CoreBundle\Entity\Outcome $outcomes
     * @return LearningEntity
     */
    public function addOutcome(Outcome $outcomes)
    {
        $this->outcomes[] = $outcomes;

        return $this;
    }

    /**
     * Remove outcomes
     *
     * @param \La\CoreBundle\Entity\Outcome $outcomes
     */
    public function removeOutcome(Outcome $outcomes)
    {
        $this->outcomes->removeElement($outcomes);
    }

    /**
     * Get outcomes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOutcomes()
    {
        return $this->outcomes;
    }

    /**
     * Set owner
     *
     * @param \La\CoreBundle\Entity\User $owner
     * @return LearningEntity
     */
    public function setOwner(User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \La\CoreBundle\Entity\User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Add uplinks
     *
     * @param \La\CoreBundle\Entity\Uplink $uplinks
     * @return LearningEntity
     */
    public function addUplink(Uplink $uplinks)
    {
        $this->uplinks[] = $uplinks;

        return $this;
    }

    /**
     * Remove uplinks
     *
     * @param \La\CoreBundle\Entity\Uplink $uplinks
     */
    public function removeUplink(Uplink $uplinks)
    {
        $this->uplinks->removeElement($uplinks);
    }

    /**
     * Get uplinks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUplinks()
    {
        return $this->uplinks;
    }

    /**
     * Add downlinks
     *
     * @param \La\CoreBundle\Entity\Uplink $downlinks
     * @return LearningEntity
     */
    public function addDownlink(\La\CoreBundle\Entity\Uplink $downlinks)
    {
        $this->downlinks[] = $downlinks;

        return $this;
    }

    /**
     * Remove downlinks
     *
     * @param \La\CoreBundle\Entity\Uplink $downlinks
     */
    public function removeDownlink(Uplink $downlinks)
    {
        $this->downlinks->removeElement($downlinks);
    }

    /**
     * Get downlinks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDownlinks()
    {
        return $this->downlinks;
    }

    /**
     * Set content
     *
     * @param \La\CoreBundle\Entity\Content $content
     * @return LearningEntity
     */
    public function setContent(Content $content = null)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return \La\CoreBundle\Entity\Content
     */
    public function getContent()
    {
        return $this->content;
    }
}
