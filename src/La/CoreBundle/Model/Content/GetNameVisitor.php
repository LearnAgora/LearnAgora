<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\CoreBundle\Model\Content;

use La\CoreBundle\Entity\HtmlContent;
use La\CoreBundle\Entity\UrlContent;
use La\CoreBundle\Entity\QuizContent;
use La\CoreBundle\Entity\MultipleChoiceQuestion;
use La\CoreBundle\Entity\SimpleQuestion;
use La\CoreBundle\Visitor\HtmlContentVisitorInterface;
use La\CoreBundle\Visitor\MultipleChoiceQuestionVisitorInterface;
use La\CoreBundle\Visitor\QuizContentVisitorInterface;
use La\CoreBundle\Visitor\SimpleQuestionVisitorInterface;
use La\CoreBundle\Visitor\UrlContentVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

class GetNameVisitor implements
    VisitorInterface,
    HtmlContentVisitorInterface,
    UrlContentVisitorInterface,
    MultipleChoiceQuestionVisitorInterface,
    SimpleQuestionVisitorInterface,
    QuizContentVisitorInterface
{
    /**
     * {@inheritdoc}
     */
    public function visitHtmlContent(HtmlContent $content)
    {
        return "HTML";
    }

    /**
     * {@inheritdoc}
     */
    public function visitUrlContent(UrlContent $content)
    {
        return "URL";
    }

    /**
     * {@inheritdoc}
     */
    public function visitMultipleChoiceQuestion(MultipleChoiceQuestion $content)
    {
        return "Multiple Choice Question";
    }

    /**
     * {@inheritdoc}
     */
    public function visitSimpleQuestion(SimpleQuestion $content)
    {
        return "Simple Question (only 2 answers, multiple select)";
    }

    /**
     * {@inheritdoc}
     */
    public function visitQuizContent(QuizContent $content)
    {
        return "Quiz";
    }
}
