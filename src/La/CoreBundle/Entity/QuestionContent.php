<?php

namespace La\CoreBundle\Entity;

use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;

/**
 * @Serializer\ExclusionPolicy("all")
 *
 * @Hateoas\Relation(
 *     "answers",
 *     href = "expr('/sandbox/content/' ~ object.getId() ~ '/answers')",
 *     embedded = "expr(object.getAnswers())",
 *     exclusion = @Hateoas\Exclusion(excludeIf = "expr(object.getAnswers().count() == 0)")
 * )
 */
abstract class QuestionContent extends Content
{
    /**
     * @var string
     *
     * @Serializer\Expose
     */
    private $instruction = '';

    /**
     * @var string
     *
     * @Serializer\Expose
     */
    private $question = '';

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @Serializer\Type("ArrayCollection<La\CoreBundle\Entity\Answer>")
     */
    private $answers;

    public function init($em = null) {
        $this->instruction = '';
    }

    /**
     * Set instruction
     *
     * @param string $instruction
     * @return QuestionContent
     */
    public function setInstruction($instruction)
    {
        $this->instruction = $instruction;

        return $this;
    }

    /**
     * Get instruction
     *
     * @return string
     */
    public function getInstruction()
    {
        return $this->instruction;
    }

    /**
     * Set question
     *
     * @param string $question
     * @return QuestionContent
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->answers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add answers
     *
     * @param \La\CoreBundle\Entity\Answer $answers
     * @return QuestionContent
     */
    public function addAnswer(\La\CoreBundle\Entity\Answer $answers)
    {
        $this->answers[] = $answers;

        return $this;
    }

    /**
     * Remove answers
     *
     * @param \La\CoreBundle\Entity\Answer $answers
     */
    public function removeAnswer(\La\CoreBundle\Entity\Answer $answers)
    {
        $this->answers->removeElement($answers);
    }

    /**
     * Get answers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnswers()
    {
        return $this->answers;
    }
}
