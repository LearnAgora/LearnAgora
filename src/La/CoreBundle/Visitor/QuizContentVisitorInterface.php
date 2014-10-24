<?php
/**
 * Created by PhpStorm.
 * User: Mihai
 * Date: 07/09/14
 * Time: 14:03
 */

namespace La\CoreBundle\Visitor;

use La\CoreBundle\Entity\QuizContent;

interface QuizContentVisitorInterface
{
    /**
     * @param QuizContent $content
     **/
    public function visitQuizContent(QuizContent $content);
}
