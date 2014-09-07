<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 8/29/14
 * Time: 11:20 PM
 */

namespace La\CoreBundle\Model;

use La\CoreBundle\Entity\HtmlContent;
use La\CoreBundle\Entity\UrlContent;
use La\CoreBundle\Entity\QuestionContent;
use La\CoreBundle\Entity\QuizContent;

interface ContentVisitorInterface {

    /**
     * @param HtmlContent $content
     **/
    public function visitHtmlContent(HtmlContent $content);

    /**
     * @param UrlContent $content
     **/
    public function visitUrlContent(UrlContent $content);

    /**
     * @param QuestionContent $content
     **/
    public function visitQuestionContent(QuestionContent $content);

    /**
     * @param QuizContent $content
     **/
    public function visitQuizContent(QuizContent $content);

}