<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Model\ContentVisitorInterface;

/**
 * Objective
 */
class QuestionContent extends Content
{
    private $instruction;
    private $question;

    public function accept(ContentVisitorInterface $visitor) {
        return $visitor->visitQuestionContent($this);
    }
    public function init() {
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $answers;

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
