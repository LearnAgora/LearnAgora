<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\CoreBundle\Model;


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
            $answerOutcome = new AnswerOutcome();
            $answerOutcome->setAnswer($answer);
            $answerOutcome->setSelected(0);
            $outcomes[] = $answerOutcome;
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
    public function visitQuizContent(QuizContent $content)
    {
        $outcomes = array();
        return $outcomes;
    }
} 