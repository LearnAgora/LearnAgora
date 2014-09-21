<?php

namespace La\CoreBundle\Entity;

use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;

/**
 * @Serializer\ExclusionPolicy("all")
 *
 * @Hateoas\Relation("self", href = "expr('/sandbox/answer/' ~ object.getId())")
 */
class Answer
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
    private $answer = '';

    /**
     * @var \La\CoreBundle\Entity\QuestionContent
     */
    private $question;

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
     * Set answer
     *
     * @param string $answer
     * @return Answer
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return string
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set question
     *
     * @param \La\CoreBundle\Entity\QuestionContent $question
     * @return Answer
     */
    public function setQuestion(\La\CoreBundle\Entity\QuestionContent $question = null)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return \La\CoreBundle\Entity\QuestionContent
     */
    public function getQuestion()
    {
        return $this->question;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $outcomes;

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
     * @param \La\CoreBundle\Entity\AnswerOutcome $outcomes
     * @return Answer
     */
    public function addOutcome(\La\CoreBundle\Entity\AnswerOutcome $outcomes)
    {
        $this->outcomes[] = $outcomes;

        return $this;
    }

    /**
     * Remove outcomes
     *
     * @param \La\CoreBundle\Entity\AnswerOutcome $outcomes
     */
    public function removeOutcome(\La\CoreBundle\Entity\AnswerOutcome $outcomes)
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
}
