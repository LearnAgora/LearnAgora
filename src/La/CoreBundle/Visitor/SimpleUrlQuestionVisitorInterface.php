<?php
/**
 * Created by PhpStorm.
 * User: Mihai
 * Date: 07/09/14
 * Time: 14:02
 */

namespace La\CoreBundle\Visitor;


use La\CoreBundle\Entity\SimpleUrlQuestion;

interface SimpleUrlQuestionVisitorInterface {
    /**
     * @param SimpleUrlQuestion $content
     **/
    public function visitSimpleUrlQuestion(SimpleUrlQuestion $content);
} 