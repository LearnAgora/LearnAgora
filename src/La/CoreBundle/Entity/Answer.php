<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Objective
 */
class Answer
{
    private $id;
    private $answer;
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
}
