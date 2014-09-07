<?php
/**
 * Created by PhpStorm.
 * User: Mihai
 * Date: 07/09/14
 * Time: 14:02
 */

namespace La\CoreBundle\Visitor;


use La\CoreBundle\Entity\QuestionContent;

interface QuestionContentVisitorInterface {
    /**
     * @param QuestionContent $content
     **/
    public function visitQuestionContent(QuestionContent $content);
} 