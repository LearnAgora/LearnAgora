<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\CoreBundle\Model;


use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\Objective;
use La\CoreBundle\Entity\Action;
use La\CoreBundle\Entity\HtmlContent;
use La\CoreBundle\Entity\SimpleUrlQuestion;
use La\CoreBundle\Entity\UrlContent;
use La\CoreBundle\Entity\QuizContent;
use La\CoreBundle\Entity\MultipleChoiceQuestion;
use La\CoreBundle\Entity\SimpleQuestion;
use La\CoreBundle\Visitor\ActionVisitorInterface;
use La\CoreBundle\Visitor\AgoraVisitorInterface;
use La\CoreBundle\Visitor\ObjectiveVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

class ContentVisitor implements VisitorInterface, AgoraVisitorInterface, ObjectiveVisitorInterface, ActionVisitorInterface
{
    /**
     * {@inheritdoc}
     */
    public function visitAgora(Agora $agora)
    {
        return array(new HtmlContent());
    }

    /**
     * {@inheritdoc}
     */
    public function visitObjective(Objective $objective)
    {
        return array(new HtmlContent());
    }

    /**
     * {@inheritdoc}
     */
    public function visitAction(Action $action)
    {
        return array(
            //new HtmlContent(),
            //new UrlContent(),
            //new SimpleQuestion(),
            new SimpleUrlQuestion(),
            //new MultipleChoiceQuestion(),
            //new QuizContent(),
        );
    }
} 