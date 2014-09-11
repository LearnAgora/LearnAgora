<?php
/**
 * Created by PhpStorm.
 * User: Mihai
 * Date: 07/09/14
 * Time: 14:02
 */

namespace La\CoreBundle\Visitor;


use La\CoreBundle\Entity\SimpleQuestion;

interface SimpleQuestionVisitorInterface {
    /**
     * @param SimpleQuestion $content
     **/
    public function visitSimpleQuestion(SimpleQuestion $content);
} 