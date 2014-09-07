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
use La\CoreBundle\Entity\QuestionContent;
use La\CoreBundle\Entity\QuizContent;
use La\CoreBundle\Forms\HtmlContentType;
use La\CoreBundle\Forms\QuestionContentType;
use La\CoreBundle\Forms\UrlContentType;
use La\CoreBundle\Visitor\HtmlContentVisitorInterface;
use La\CoreBundle\Visitor\QuestionContentVisitorInterface;
use La\CoreBundle\Visitor\QuizContentVisitorInterface;
use La\CoreBundle\Visitor\UrlContentVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

class GetAdminFormVisitor implements VisitorInterface, HtmlContentVisitorInterface, UrlContentVisitorInterface, QuestionContentVisitorInterface, QuizContentVisitorInterface
{
    /**
     * {@inheritdocm
     */
    public function visitHtmlContent(HtmlContent $content)
    {
        return new HtmlContentType();
    }

    /**
     * {@inheritdoc}
     */
    public function visitUrlContent(UrlContent $content)
    {
        return new UrlContentType();
    }

    /**
     * {@inheritdoc}
     */
    public function visitQuestionContent(QuestionContent $content)
    {
        $form = new QuestionContentType();
        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function visitQuizContent(QuizContent $content)
    {
        return "Quiz";
    }
} 