<?php

namespace La\CoreBundle\Entity;

use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @Serializer\ExclusionPolicy("all")
 *
 * @Hateoas\Relation("self", href = "expr('/sandbox/lom/' ~ object.getId())")
 */
class Lom
{
    /**
     * @var int
     *
     * @Serializer\Expose
     */
    private $id;

    /**
     * @var string
     *
     * @Serializer\Expose
     */
    private $instruction = '';

    /**
     * @var string
     * @Serializer\Expose
     */
    private $url = '';

    /**
     * @var LearningContent
     */
    private $learningContent;

    /**
     * @var Collection
     */
    private $outcomes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->outcomes = new ArrayCollection();
    }

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
     * @param $instruction string
     * @return Lom
     */
    public function setInstruction($instruction) {
        $this->instruction = $instruction;
        return $this;
    }

    /**
     * @return string
     */
    public function getInstruction() {
        return $this->instruction;
    }

    /**
     * @param $url string
     * @return Lom
     */
    public function setUrl($url) {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @param LearningContent $learningContent
     * @return Lom
     */
    public function setLearningContent($learningContent) {
        $this->learningContent = $learningContent;
        return $this;
    }

    /**
     * @return LearningContent
     */
    public function getLearningContent() {
        return $this->learningContent;
    }

    /**
     * Add outcomes
     *
     * @param LearningOutcome $outcomes
     * @return Answer
     */
    public function addOutcome(LearningOutcome $outcomes)
    {
        $this->outcomes[] = $outcomes;

        return $this;
    }

    /**
     * Remove outcomes
     *
     * @param LearningOutcome $outcomes
     */
    public function removeOutcome(LearningOutcome $outcomes)
    {
        $this->outcomes->removeElement($outcomes);
    }

    /**
     * Get outcomes
     *
     * @return Collection
     */
    public function getOutcomes()
    {
        return $this->outcomes;
    }
}
