<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\LearnodexBundle\Model\Visitor;

use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\Action;
use La\CoreBundle\Entity\AnswerOutcome;
use La\CoreBundle\Entity\ButtonOutcome;
use La\CoreBundle\Entity\HtmlContent;
use La\CoreBundle\Entity\Objective;
use La\CoreBundle\Entity\SimpleUrlQuestion;
use La\CoreBundle\Entity\Techne;
use La\CoreBundle\Entity\UrlOutcome;
use La\CoreBundle\Visitor\ActionVisitorInterface;
use La\CoreBundle\Visitor\AgoraVisitorInterface;
use La\CoreBundle\Visitor\ObjectiveVisitorInterface;
use La\CoreBundle\Visitor\TechneVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * @DI\Service("la_learnodex.initialise_learning_entity_visitor_no_persist")
 */
class InitialiseLearningEntityNoPersistVisitor implements
    VisitorInterface,
    TechneVisitorInterface,
    AgoraVisitorInterface,
    ObjectiveVisitorInterface,
    ActionVisitorInterface
{

    /**
     * {@inheritdoc}
     */
    public function visitTechne(Techne $learningEntity)
    {
        $content = new HtmlContent();
        $learningEntity->setContent($content);
    }

    /**
     * {@inheritdoc}
     */
    public function visitAgora(Agora $learningEntity)
    {
        $content = new HtmlContent();
        $learningEntity->setContent($content);
    }

    /**
     * {@inheritdoc}
     */
    public function visitObjective(Objective $learningEntity)
    {
        $content = new HtmlContent();
        $learningEntity->setContent($content);
    }

    /**
     * {@inheritdoc}
     */
    public function visitAction(Action $learningEntity)
    {
        $content = $learningEntity->getContent();
        if (is_null($content)) {
            $content = new SimpleUrlQuestion();
            //$content->init($this->entityManager);
            $learningEntity->setContent($content);
        }

        //add the "Discard" Outcome
        $outcome = new ButtonOutcome();
        $outcome->setCaption("DISCARD");
        $outcome->setLearningEntity($learningEntity);
        $outcome->setAffinity(0);
        $learningEntity->addOutcome($outcome);

        //add the "Later" Outcome
        $outcome = new ButtonOutcome();
        $outcome->setCaption("LATER");
        $outcome->setLearningEntity($learningEntity);
        $outcome->setAffinity(20);
        $learningEntity->addOutcome($outcome);

        //add the "URL" Outcome
        $outcome = new UrlOutcome();
        $outcome->setLearningEntity($learningEntity);
        $outcome->setAffinity(40);
        $learningEntity->addOutcome($outcome);

        //add an outcome for each answer
        foreach ($content->getAnswers() as $answer) {
            $outcome = new AnswerOutcome();
            $outcome->setAnswer($answer);
            $outcome->setSelected(1);
            $outcome->setLearningEntity($learningEntity);
            $outcome->setAffinity(0);
            $learningEntity->addOutcome($outcome);
        }
    }
}
