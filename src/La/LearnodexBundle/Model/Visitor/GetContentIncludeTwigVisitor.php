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
use La\CoreBundle\Visitor\ActionVisitorInterface;
use La\CoreBundle\Visitor\AgoraVisitorInterface;
use La\CoreBundle\Visitor\HtmlContentVisitorInterface;
use La\CoreBundle\Visitor\ObjectiveVisitorInterface;
use La\CoreBundle\Visitor\QuestionContentVisitorInterface;
use La\CoreBundle\Visitor\UrlContentVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

class GetContentIncludeTwigVisitor implements
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
        return 'LaLearnodexBundle:Card:Content/HtmlContent.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function visitObjective(Objective $objective)
    {
        return 'LaLearnodexBundle:Card:Content/HtmlContent.html.twig';
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
        return 'LaLearnodexBundle:Card:Content/HtmlContent.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function visitUrlContent(UrlContent $content){
        return 'LaLearnodexBundle:Card:Content/UrlContent.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function visitQuestionContent(QuestionContent $content){
        return 'LaLearnodexBundle:Card:Content/QuestionContent.html.twig';
    }
}