<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\CoreBundle\Model;


use La\CoreBundle\Entity\AffinityResult;
use La\CoreBundle\Entity\HtmlContent;
use La\CoreBundle\Entity\UrlContent;
use La\CoreBundle\Entity\QuestionContent;
use La\CoreBundle\Entity\QuizContent;
//use La\CoreBundle\Model\AnswerOutcome;
use La\CoreBundle\Entity\AnswerOutcome;
use La\CoreBundle\Visitor\HtmlContentVisitorInterface;
use La\CoreBundle\Visitor\QuestionContentVisitorInterface;
use La\CoreBundle\Visitor\QuizContentVisitorInterface;
use La\CoreBundle\Visitor\UrlContentVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

class PossibleOutcomeActionVisitor implements VisitorInterface, HtmlContentVisitorInterface, UrlContentVisitorInterface, QuestionContentVisitorInterface, QuizContentVisitorInterface
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
    public function visitQuestionContent(QuestionContent $content)
    {
        $outcomes = array();
        foreach ($content->getAnswers() as $answer) {
            $answerOutcomeNotSelected = new AnswerOutcome();
            $answerOutcomeNotSelected->setAnswer($answer);
            $answerOutcomeNotSelected->setSelected(0);
            $result = new AffinityResult();
            $answerOutcomeNotSelected->addResult($result);
            $outcomes[] = $answerOutcomeNotSelected;
            $answerOutcomeSelected = new AnswerOutcome();
            $answerOutcomeSelected->setAnswer($answer);
            $answerOutcomeSelected->setSelected(1);
            $result = new AffinityResult();
            $answerOutcomeSelected->addResult($result);
            $outcomes[] = $answerOutcomeSelected;
        }
        return $outcomes;
    }

    /**
     * {@inheritdoc}
     */
    public function visitQuizContent(QuizContent $content)
    {
        $outcomes = array();
        return $outcomes;
    }
} 