<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\LearnodexBundle\Model\Visitor;

use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\Action;
use La\CoreBundle\Entity\HtmlContent;
use La\CoreBundle\Entity\MultipleChoiceQuestion;
use La\CoreBundle\Entity\Objective;
use La\CoreBundle\Entity\SimpleQuestion;
use La\CoreBundle\Entity\UrlContent;
use La\CoreBundle\Visitor\MultipleChoiceQuestionVisitorInterface;
use La\CoreBundle\Visitor\SimpleQuestionVisitorInterface;
use La\CoreBundle\Visitor\ActionVisitorInterface;
use La\CoreBundle\Visitor\AgoraVisitorInterface;
use La\CoreBundle\Visitor\HtmlContentVisitorInterface;
use La\CoreBundle\Visitor\ObjectiveVisitorInterface;
use La\CoreBundle\Visitor\UrlContentVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;
use La\LearnodexBundle\Forms\HtmlContentType;
use La\LearnodexBundle\Forms\MultipleChoiceQuestionType;
use La\LearnodexBundle\Forms\SimpleQuestionType;
use La\LearnodexBundle\Forms\SimpleUrlQuestionType;
use La\LearnodexBundle\Forms\UrlContentType;


class GetContentFormVisitor implements
    VisitorInterface,
    AgoraVisitorInterface,
    ObjectiveVisitorInterface,
    ActionVisitorInterface,
    HtmlContentVisitorInterface,
    UrlContentVisitorInterface,
    MultipleChoiceQuestionVisitorInterface,
    SimpleQuestionVisitorInterface
{
    /**
     * {@inheritdoc}
     */
    public function visitAgora(Agora $agora)
    {
        return new HtmlContentType();
    }

    /**
     * {@inheritdoc}
     */
    public function visitObjective(Objective $objective)
    {
        return new HtmlContentType();
    }
    /**
     * {@inheritdoc}
     */
    public function visitAction(Action $action)
    {
        return new SimpleUrlQuestionType();
        //$content = $action->getContent();
        //return $content->accept($this);
    }

    /**
     * {@inheritdoc}
     */
    public function visitHtmlContent(HtmlContent $content){
        return new HtmlContentType();
    }

    /**
     * {@inheritdoc}
     */
    public function visitUrlContent(UrlContent $content){
        return new UrlContentType();
    }

    /**
     * {@inheritdoc}
     */
    public function visitMultipleChoiceQuestion(MultipleChoiceQuestion $content){
        return new MultipleChoiceQuestionType();
    }

    /**
     * {@inheritdoc}
     */
    public function visitSimpleQuestion(SimpleQuestion $content){
        return new SimpleQuestionType();
    }
}