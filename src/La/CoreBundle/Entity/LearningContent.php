<?php

namespace La\CoreBundle\Entity;

use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use La\CoreBundle\Visitor\LEarningContentVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * @Serializer\ExclusionPolicy("all")
 *
 * @Hateoas\Relation(
 *     "loms",
 *     href = "expr('/sandbox/content/' ~ object.getId() ~ '/loms')",
 *     embedded = "expr(object.getLoms())",
 *     exclusion = @Hateoas\Exclusion(excludeIf = "expr(object.getLoms().count() == 0)")
 * )
 */
class LearningContent extends Content
{
    /**
     * @var string
     *
     * @Serializer\Expose
     */
    private $description = '';

    /**
     * @var Collection
     */
    private $loms;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->loms = new ArrayCollection();
    }

    public function init($em = null)
    {

    }


    /**
     * Set instruction
     *
     * @param string $description
     * @return LearningContent
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }


    /**
     * Add lom
     *
     * @param Lom $lom
     * @return LearningContent
     */
    public function addLom(Lom $lom)
    {
        $this->loms[] = $lom;

        return $this;
    }

    /**
     * Remove lom
     *
     * @param Lom $lom
     */
    public function removeLom(Lom $lom)
    {
        $this->loms->removeElement($lom);
    }

    /**
     * Get answers
     *
     * @return Collection
     */
    public function getLoms()
    {
        return $this->loms;
    }


    public function accept(VisitorInterface $visitor)
    {
        if ($visitor instanceof LearningContentVisitorInterface) {
            return $visitor->visitLearningContent($this);
        }

        return null;
    }

}
