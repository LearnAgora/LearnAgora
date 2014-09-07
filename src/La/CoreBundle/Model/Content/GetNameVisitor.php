<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\CoreBundle\Model\Content;


use La\CoreBundle\Model\ContentVisitorInterface;
use La\CoreBundle\Entity\HtmlContent;
use La\CoreBundle\Entity\UrlContent;
use La\CoreBundle\Entity\QuestionContent;
use La\CoreBundle\Entity\QuizContent;

class GetNameVisitor implements ContentVisitorInterface
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
        return "Url";
    }

    /**
     * {@inheritdoc}
     */
    public function visitQuestionContent(QuestionContent $content)
    {
        return "Question";
    }

    /**
     * {@inheritdoc}
     */
    public function visitQuizContent(QuizContent $content)
    {
        return "Quiz";
    }

} 