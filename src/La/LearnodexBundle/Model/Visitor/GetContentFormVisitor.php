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
use La\CoreBundle\Entity\Objective;
use La\CoreBundle\Entity\QuestionContent;
use La\CoreBundle\Entity\UrlContent;
use La\LearnodexBundle\Forms\HtmlContentType;
use La\LearnodexBundle\Forms\UrlContentType;
use La\LearnodexBundle\Forms\QuestionContentType;
use La\CoreBundle\Visitor\ActionVisitorInterface;
use La\CoreBundle\Visitor\AgoraVisitorInterface;
use La\CoreBundle\Visitor\HtmlContentVisitorInterface;
use La\CoreBundle\Visitor\ObjectiveVisitorInterface;
use La\CoreBundle\Visitor\QuestionContentVisitorInterface;
use La\CoreBundle\Visitor\UrlContentVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;


class GetContentFormVisitor implements
    VisitorInterface,
    AgoraVisitorInterface,
    ObjectiveVisitorInterface,
    ActionVisitorInterface,
    HtmlContentVisitorInterface,
    UrlContentVisitorInterface,
    QuestionContentVisitorInterface
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
        $content = $action->getContent();
        return $content->accept($this);
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
    public function visitQuestionContent(QuestionContent $content){
        return new QuestionContentType();
    }
}