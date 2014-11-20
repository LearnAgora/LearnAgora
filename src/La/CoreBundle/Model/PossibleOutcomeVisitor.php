<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\CoreBundle\Model;

use La\CoreBundle\Entity\AffinityOutcome;
use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\Action;
use La\CoreBundle\Entity\AnswerOutcome;
use La\CoreBundle\Entity\ButtonOutcome;
use La\CoreBundle\Entity\HtmlContent;
use La\CoreBundle\Entity\MultipleChoiceQuestion;
use La\CoreBundle\Entity\Objective;
use La\CoreBundle\Entity\SimpleQuestion;
use La\CoreBundle\Entity\SimpleUrlQuestion;
use La\CoreBundle\Entity\UrlContent;
use La\CoreBundle\Entity\UrlOutcome;
use La\CoreBundle\Visitor\ActionVisitorInterface;
use La\CoreBundle\Visitor\AgoraVisitorInterface;
use La\CoreBundle\Visitor\HtmlContentVisitorInterface;
use La\CoreBundle\Visitor\MultipleChoiceQuestionVisitorInterface;
use La\CoreBundle\Visitor\ObjectiveVisitorInterface;
use La\CoreBundle\Visitor\SimpleQuestionVisitorInterface;
use La\CoreBundle\Visitor\SimpleUrlQuestionVisitorInterface;
use La\CoreBundle\Visitor\UrlContentVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

class PossibleOutcomeVisitor implements
    VisitorInterface,
    AgoraVisitorInterface,
    ObjectiveVisitorInterface,
    ActionVisitorInterface,
    HtmlContentVisitorInterface,
    UrlContentVisitorInterface,
    MultipleChoiceQuestionVisitorInterface,
    SimpleQuestionVisitorInterface,
    SimpleUrlQuestionVisitorInterface

{
    /**
     * {@inheritdoc}
     */
    public function visitAgora(Agora $agora)
    {
        //$outcome = new AffinityOutcome();
        //$outcomes = array($outcome);
        $outcomes = array();
        return $outcomes;
    }

    /**
     * {@inheritdoc}
     */
    public function visitObjective(Objective $objective)
    {
        $outcomes = array();
        return $outcomes;
    }
    /**
     * {@inheritdoc}
     */
    public function visitAction(Action $action)
    {
        $content = $action->getContent();
        if ($content) {
            return $content->accept($this);
        }
    }

    /**
     * {@inheritdocm
     */
    public function visitHtmlContent(HtmlContent $content)
    {
        $outcomes = array();
        return $outcomes;
    }

    /**
     * {@inheritdoc}
     */
    public function visitUrlContent(UrlContent $content)
    {
        $outcomes = array();
        return $outcomes;
    }

    /**
     * {@inheritdoc}
     */
    public function visitMultipleChoiceQuestion(MultipleChoiceQuestion $content)
    {
        $outcomes = array();
        foreach ($content->getAnswers() as $answer) {
            $answerOutcome = new AnswerOutcome();
            $answerOutcome->setAnswer($answer);
            $answerOutcome->setSelected(1);
            $outcomes[] = $answerOutcome;
        }
        return $outcomes;
    }
    /**
     * {@inheritdoc}
     */
    public function visitSimpleQuestion(SimpleQuestion $content)
    {
        $outcomes = array();
        foreach ($content->getAnswers() as $answer) {
            $answerOutcome = new AnswerOutcome();
            $answerOutcome->setAnswer($answer);
            $answerOutcome->setSelected(1);
            $outcomes[] = $answerOutcome;
        }
        return $outcomes;
    }

    /**
     * {@inheritdoc}
     */
    public function visitSimpleUrlQuestion(SimpleUrlQuestion $content)
    {
        $outcomes = $this->getDefaultActionOutcomes();

        $urlOutcome = new UrlOutcome();
        $outcomes[] = $urlOutcome;

        foreach ($content->getAnswers() as $answer) {
            $answerOutcome = new AnswerOutcome();
            $answerOutcome->setAnswer($answer);
            $answerOutcome->setSelected(1);
            $outcomes[] = $answerOutcome;
        }
        return $outcomes;
    }

    private function getDefaultActionOutcomes()
    {
        $defaultOutcomes = array();

        $buttonOutcome = new ButtonOutcome();
        $buttonOutcome->setCaption('DISCARD');
        $defaultOutcomes[] = $buttonOutcome;
        $buttonOutcome = new ButtonOutcome();
        $buttonOutcome->setCaption('LATER');
        $defaultOutcomes[] = $buttonOutcome;

        return $defaultOutcomes;
    }

}
