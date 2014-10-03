<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\LearnodexBundle\Model\Visitor;


use La\CoreBundle\Entity\AffinityResult;
use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\Action;
use La\CoreBundle\Entity\ButtonOutcome;
use La\CoreBundle\Entity\HtmlContent;
use La\CoreBundle\Entity\Objective;
use La\CoreBundle\Entity\SimpleUrlQuestion;
use La\CoreBundle\Entity\UrlOutcome;
use La\CoreBundle\Visitor\ActionVisitorInterface;
use La\CoreBundle\Visitor\AgoraVisitorInterface;
use La\CoreBundle\Visitor\ObjectiveVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

class InitialiseLearningEntityVisitor implements
    VisitorInterface,
    AgoraVisitorInterface,
    ObjectiveVisitorInterface,
    ActionVisitorInterface
{
    private $em;

    public function __construct($em)
    {
        $this->em = $em;
    }
    /**
     * {@inheritdocm
     */
    public function visitAgora(Agora $learningEntity)
    {
        $content = new HtmlContent();
        $learningEntity->setContent($content);
        $this->em->persist($content);
        $this->em->persist($learningEntity);
        $this->em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function visitObjective(Objective $learningEntity)
    {
        $content = new HtmlContent();
        $learningEntity->setContent($content);
        $this->em->persist($content);
        $this->em->persist($learningEntity);
        $this->em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function visitAction(Action $learningEntity)
    {
        $content = new SimpleUrlQuestion();
        $content->init($this->em);
        $learningEntity->setContent($content);
        $this->em->persist($content);
        $this->em->persist($learningEntity);

        //add the "Discard" Outcome
        $outcome = new ButtonOutcome();
        $result = new AffinityResult();
        $result->setValue(-50);
        $result->setOutcome($outcome);
        $outcome->addResult($result);
        $outcome->setCaption("DISCARD");
        $outcome->setLearningEntity($learningEntity);
        $this->em->persist($outcome);
        $this->em->persist($result);

        //add the "Later" Outcome
        $outcome = new ButtonOutcome();
        $result = new AffinityResult();
        $result->setValue(10);
        $result->setOutcome($outcome);
        $outcome->addResult($result);
        $outcome->setCaption("LATER");
        $outcome->setLearningEntity($learningEntity);
        $this->em->persist($outcome);
        $this->em->persist($result);

        $outcome = new UrlOutcome();
        $result = new AffinityResult();
        $result->setValue(40);
        $result->setOutcome($outcome);
        $outcome->addResult($result);
        $outcome->setLearningEntity($learningEntity);
        $this->em->persist($outcome);
        $this->em->persist($result);

        $this->em->flush();
    }

}