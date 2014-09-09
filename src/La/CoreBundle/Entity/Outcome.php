<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * Outcome
 */
abstract class Outcome
{
    /**
     * @var integer
     */
    private $id;

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

    abstract function accept(VisitorInterface $visitor);


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
