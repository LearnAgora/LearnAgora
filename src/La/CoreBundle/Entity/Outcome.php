<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Outcome
 */
class Outcome
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $operator;

    /**
     * @var integer
     */
    private $treshold;


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
     * Set subject
     *
     * @param string $subject
     * @return Outcome
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string 
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set operator
     *
     * @param string $operator
     * @return Outcome
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * Get operator
     *
     * @return string 
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Set treshold
     *
     * @param integer $treshold
     * @return Outcome
     */
    public function setTreshold($treshold)
    {
        $this->treshold = $treshold;

        return $this;
    }

    /**
     * Get treshold
     *
     * @return integer 
     */
    public function getTreshold()
    {
        return $this->treshold;
    }
    /**
     * @var \La\CoreBundle\Entity\LearningEntity
     */
    private $learningEntity;


    /**
     * Set learningEntity
     *
     * @param \La\CoreBundle\Entity\LearningEntity $learningEntity
     * @return Outcome
     */
    public function setLearningEntity(\La\CoreBundle\Entity\LearningEntity $learningEntity = null)
    {
        $this->learningEntity = $learningEntity;

        return $this;
    }

    /**
     * Get learningEntity
     *
     * @return \La\CoreBundle\Entity\LearningEntity 
     */
    public function getLearningEntity()
    {
        return $this->learningEntity;
    }


    //this needs to change!!! But i can find a way to have my objects + associated forms in my twig viewer
    private $_form;

    public function setForm($form)
    {
        $this->_form = $form;
    }
    public function getForm()
    {
        return $this->_form;
    }

}
