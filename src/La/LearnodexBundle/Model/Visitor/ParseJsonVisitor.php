<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\LearnodexBundle\Model\Visitor;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\Action;
use La\CoreBundle\Entity\Answer;
use La\CoreBundle\Entity\AnswerOutcome;
use La\CoreBundle\Entity\ButtonOutcome;
use La\CoreBundle\Entity\Domain;
use La\CoreBundle\Entity\HtmlContent;
use La\CoreBundle\Entity\Objective;
use La\CoreBundle\Entity\SimpleUrlQuestion;
use La\CoreBundle\Entity\Techne;
use La\CoreBundle\Entity\UrlOutcome;
use La\CoreBundle\Visitor\ActionVisitorInterface;
use La\CoreBundle\Visitor\AgoraVisitorInterface;
use La\CoreBundle\Visitor\DomainVisitorInterface;
use La\CoreBundle\Visitor\ObjectiveVisitorInterface;
use La\CoreBundle\Visitor\TechneVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;


class ParseJsonVisitor implements
    VisitorInterface,
    DomainVisitorInterface,
    TechneVisitorInterface,
    AgoraVisitorInterface,
    ObjectiveVisitorInterface,
    ActionVisitorInterface
{
    private $jsonEntity;

    /**
     * @var ObjectManager
     */
    private $entityManager;
    private $isNew;

    public function __construct($jsonEntity, $entityManager, $isNew = false)
    {
        $this->jsonEntity = $jsonEntity;
        $this->entityManager = $entityManager;
        $this->isNew = $isNew;
    }

    /**
     * {@inheritdoc}
     */
    public function visitDomain(Domain $learningEntity)
    {
        /* @var $content HtmlContent */
        if ($this->isNew) {
            $content = new HtmlContent();
            $learningEntity->setContent($content);
        } else {
            $content = $learningEntity->getContent();
        }

        $jsonContent = $this->jsonEntity->_embeddedItems->content;
        $content->setContent($jsonContent->content);
        $this->entityManager->persist($content);
        $this->entityManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function visitTechne(Techne $learningEntity)
    {
        /* @var $content HtmlContent */
        if ($this->isNew) {
            $content = new HtmlContent();
            $learningEntity->setContent($content);
        } else {
            $content = $learningEntity->getContent();
        }

        $jsonContent = $this->jsonEntity->_embeddedItems->content;
        $content->setContent($jsonContent->content);
        $this->entityManager->persist($content);
        $this->entityManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function visitAgora(Agora $learningEntity)
    {
        /* @var $content HtmlContent */
        if ($this->isNew) {
            $content = new HtmlContent();
            $learningEntity->setContent($content);
        } else {
            $content = $learningEntity->getContent();
        }

        $jsonContent = $this->jsonEntity->_embeddedItems->content;
        $content->setContent($jsonContent->content);
        $this->entityManager->persist($content);
        $this->entityManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function visitObjective(Objective $learningEntity)
    {
        /* @var $content HtmlContent */
        if ($this->isNew) {
            $content = new HtmlContent();
            $learningEntity->setContent($content);
        } else {
            $content = $learningEntity->getContent();
        }

        $jsonContent = $this->jsonEntity->_embeddedItems->content;
        $content->setContent($jsonContent->content);
        $this->entityManager->persist($content);
        $this->entityManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function visitAction(Action $learningEntity)
    {
        if ($this->isNew) {
            $jsonContent = $this->jsonEntity->_embeddedItems->content;
            /* @var $content SimpleUrlQuestion */
            $content = new SimpleUrlQuestion();
            $learningEntity->setContent($content);
            $content->setInstruction($jsonContent->instruction);
            $content->setQuestion($jsonContent->question);
            $content->setUrl($jsonContent->url);
            $this->entityManager->persist($content);

            $jsonOutcomes = $this->jsonEntity->_embeddedItems->outcomes;
            foreach ($jsonOutcomes as $jsonOutcome) {
                $outcome = null;
                switch ($jsonOutcome->subject) {
                    case "answer" :
                        $outcome = new AnswerOutcome();
                        $outcome->setSelected(1);
                        $outcome->setAffinity($jsonOutcome->affinity);

                        $jsonAnswer = $jsonOutcome->answer;
                        $answer = new Answer();
                        $answer->setQuestion($content);
                        $answer->setAnswer($jsonAnswer->answer);
                        $outcome->setAnswer($answer);
                        $outcome->setLearningEntity($learningEntity);

                        $this->entityManager->persist($outcome);
                        $this->entityManager->persist($answer);
                        break;
                    case "button" :
                        $outcome = new ButtonOutcome();
                        $outcome->setCaption($jsonOutcome->caption);
                        $outcome->setAffinity($jsonOutcome->affinity);
                        $outcome->setLearningEntity($learningEntity);
                        $this->entityManager->persist($outcome);
                        break;
                    case "url" :
                        $outcome = new UrlOutcome();
                        $outcome->setAffinity($jsonOutcome->affinity);
                        $outcome->setLearningEntity($learningEntity);
                        $this->entityManager->persist($outcome);
                        break;
                }
                if ($outcome) {
                    $learningEntity->addOutcome($outcome);
                }
            }
        } else {
            $jsonContent = $this->jsonEntity->_embeddedItems->content;
            /* @var $content SimpleUrlQuestion */
            $content = $learningEntity->getContent();
            $content->setInstruction($jsonContent->instruction);
            $content->setQuestion($jsonContent->question);
            $content->setUrl($jsonContent->url);
            $this->entityManager->persist($content);

            $jsonOutcomes = $this->jsonEntity->_embeddedItems->outcomes;
            foreach ($jsonOutcomes as $jsonOutcome) {
                if ($jsonOutcome->subject == "answer") {
                    if (isset($jsonOutcome->deleted) && $jsonOutcome->deleted) {
                        /** @var $outcome AnswerOutcome */
                        $outcome = $this->entityManager->getRepository('LaCoreBundle:AnswerOutcome')->find($jsonOutcome->id);
                        $jsonAnswer = $jsonOutcome->answer;
                        /** @var $answer Answer */
                        $answer = $this->entityManager->getRepository('LaCoreBundle:Answer')->find($jsonAnswer->id);
                        foreach ($outcome->getProbabilities() as $outcomeProbability) {
                            $this->entityManager->remove($outcomeProbability);
                        }
                        foreach ($outcome->getTraces() as $trace) {
                            $this->entityManager->remove($trace);
                        }
                        $this->entityManager->remove($outcome);
                        $this->entityManager->remove($answer);

                    } else {
                        if ($jsonOutcome->id) {
                            /** @var $outcome AnswerOutcome */
                            $outcome = $this->entityManager->getRepository('LaCoreBundle:AnswerOutcome')->find($jsonOutcome->id);
                        } else {
                            $outcome = new AnswerOutcome();
                            $outcome->setSelected(1);
                        }
                        $outcome->setAffinity($jsonOutcome->affinity);

                        $jsonAnswer = $jsonOutcome->answer;
                        if ($jsonAnswer->id) {
                            /** @var $answer Answer */
                            $answer = $this->entityManager->getRepository('LaCoreBundle:Answer')->find($jsonAnswer->id);
                        } else {
                            $answer = new Answer();
                            $answer->setQuestion($content);
                        }
                        $answer->setAnswer($jsonAnswer->answer);
                        $outcome->setAnswer($answer);
                        $outcome->setLearningEntity($learningEntity);

                        $this->entityManager->persist($outcome);
                        $this->entityManager->persist($answer);
                    }
                }

            }
        }
        $this->entityManager->flush();
    }
}
