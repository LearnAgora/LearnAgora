<?php

namespace La\CoreBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;
use La\CoreBundle\Visitor\VisitableInterface;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * @Serializer\ExclusionPolicy("all")
 *
 * @Hateoas\Relation(
 *     "content",
 *     embedded = "expr(object.getContent())",
 *     exclusion = @Hateoas\Exclusion(excludeIf = "expr(object.getContent() === null)")
 * )
 * @Hateoas\Relation(
 *     "outcomes",
 *     embedded = "expr(object.getOutcomes())",
 *     exclusion = @Hateoas\Exclusion(excludeIf = "expr(object.getOutcomes() === null)")
 * )
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
     *
     * @Serializer\Expose
     */
    private $owner;

    /**
     * @var Collection
     */
    private $outcomes;

    /**
     * @var Collection
     */
    private $progress;

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
     * @var Collection
     */
    private $userProbabilities;
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
     * @return string
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

    abstract public function accept(VisitorInterface $visitor);

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

    /**
     * Add progress
     *
     * @param \La\CoreBundle\Entity\Progress $progress
     * @return LearningEntity
     */
    public function addProgress(\La\CoreBundle\Entity\Progress $progress)
    {
        $this->progress[] = $progress;

        return $this;
    }

    /**
     * Remove progress
     *
     * @param \La\CoreBundle\Entity\Progress $progress
     */
    public function removeProgress(\La\CoreBundle\Entity\Progress $progress)
    {
        $this->progress->removeElement($progress);
    }

    /**
     * Get progress
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgress()
    {
        return $this->progress;
    }

    /**
     * Add userProbabilities
     *
     * @param \La\CoreBundle\Entity\UserProbability $userProbabilities
     * @return LearningEntity
     */
    public function addUserProbability(\La\CoreBundle\Entity\UserProbability $userProbabilities)
    {
        $this->userProbabilities[] = $userProbabilities;

        return $this;
    }

    /**
     * Remove userProbabilities
     *
     * @param \La\CoreBundle\Entity\UserProbability $userProbabilities
     */
    public function removeUserProbability(\La\CoreBundle\Entity\UserProbability $userProbabilities)
    {
        $this->userProbabilities->removeElement($userProbabilities);
    }

    /**
     * Get userProbabilities
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUserProbabilities()
    {
        return $this->userProbabilities;
    }
}
