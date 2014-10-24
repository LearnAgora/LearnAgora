<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\CoreBundle\Model;

use La\CoreBundle\Entity\AffinityResult;
use La\CoreBundle\Entity\ButtonOutcome;
use La\CoreBundle\Entity\HtmlContent;
use La\CoreBundle\Entity\MultipleChoiceQuestion;
use La\CoreBundle\Entity\SimpleQuestion;
use La\CoreBundle\Entity\SimpleUrlQuestion;
use La\CoreBundle\Entity\UrlContent;
use La\CoreBundle\Entity\AnswerOutcome;
use La\CoreBundle\Entity\UrlOutcome;
use La\CoreBundle\Visitor\HtmlContentVisitorInterface;
use La\CoreBundle\Visitor\MultipleChoiceQuestionVisitorInterface;
use La\CoreBundle\Visitor\SimpleQuestionVisitorInterface;
use La\CoreBundle\Visitor\SimpleUrlQuestionVisitorInterface;
use La\CoreBundle\Visitor\UrlContentVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

class PossibleOutcomeActionVisitor implements
    VisitorInterface,
    HtmlContentVisitorInterface,
    UrlContentVisitorInterface,
    MultipleChoiceQuestionVisitorInterface,
    SimpleQuestionVisitorInterface,
    SimpleUrlQuestionVisitorInterface
{
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
            $result = new AffinityResult();
            $answerOutcome->addResult($result);
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
            $result = new AffinityResult();
            $answerOutcome->addResult($result);
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
        $result = new AffinityResult();
        $urlOutcome->addResult($result);
        $outcomes[] = $urlOutcome;

        foreach ($content->getAnswers() as $answer) {
            $answerOutcome = new AnswerOutcome();
            $answerOutcome->setAnswer($answer);
            $answerOutcome->setSelected(1);
            $result = new AffinityResult();
            $answerOutcome->addResult($result);
            $outcomes[] = $answerOutcome;
        }
        return $outcomes;
    }

    private function getDefaultActionOutcomes()
    {
        $defaultOutcomes = array();

        $buttonOutcome = new ButtonOutcome();
        $buttonOutcome->setCaption('DISCARD');
        $result = new AffinityResult();
        $buttonOutcome->addResult($result);
        $defaultOutcomes[] = $buttonOutcome;
        $buttonOutcome = new ButtonOutcome();
        $buttonOutcome->setCaption('LATER');
        $result = new AffinityResult();
        $buttonOutcome->addResult($result);
        $defaultOutcomes[] = $buttonOutcome;

        return $defaultOutcomes;
    }
}
