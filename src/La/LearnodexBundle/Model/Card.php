<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/9/14
 * Time: 9:10 AM
 */

namespace La\LearnodexBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Entity\LearningEntity;
use La\LearnodexBundle\Model\Visitor\GetContentTwigVisitor;
use La\LearnodexBundle\Model\Visitor\GetOutcomeTwigVisitor;

class Card {
    protected $learningEntity = null;

    /**
     * @param LearningEntity $learningEntity
     **/
    public function __construct(LearningEntity $learningEntity) {
        $this->learningEntity = $learningEntity;
    }

    public function getLearningEntity() {
        return $this->learningEntity;
    }
    public function getId() {
        return $this->learningEntity->getId();
    }
    public function getName() {
        return $this->learningEntity->getName();
    }
    public function getContent() {
        return $this->learningEntity->getContent();
    }

    public function getContentTwig() {
        $getContentTwigVisitor = new GetContentTwigVisitor();
        return $this->learningEntity->accept($getContentTwigVisitor);
    }

    public function getOutcomeTwig() {
        $getOutcomeTwigVisitor = new GetOutcomeTwigVisitor();
        return $this->learningEntity->accept($getOutcomeTwigVisitor);
    }

    public function getOutcomes() {
        return array();
    }

    public function getPossibleOutcomes() {
        return array();
    }

} 