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
use La\CoreBundle\Visitor\ActionVisitorInterface;
use La\CoreBundle\Visitor\AgoraVisitorInterface;
use La\CoreBundle\Visitor\HtmlContentVisitorInterface;
use La\CoreBundle\Visitor\MultipleChoiceQuestionVisitorInterface;
use La\CoreBundle\Visitor\ObjectiveVisitorInterface;
use La\CoreBundle\Visitor\SimpleQuestionVisitorInterface;
use La\CoreBundle\Visitor\UrlContentVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

class GetContentTwigVisitor implements
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
        return 'LaLearnodexBundle:Admin:Content/HtmlContent.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function visitObjective(Objective $objective)
    {
        return 'LaLearnodexBundle:Admin:Content/HtmlContent.html.twig';
    }
    /**
     * {@inheritdoc}
     */
    public function visitAction(Action $action)
    {
        return 'LaLearnodexBundle:Admin:Content/SimpleUrlQuestion.html.twig';
        //$content = $action->getContent();
        //return $content->accept($this);
    }

    /**
     * {@inheritdoc}
     */
    public function visitHtmlContent(HtmlContent $content)
    {
        return 'LaLearnodexBundle:Admin:Content/HtmlContent.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function visitUrlContent(UrlContent $content)
    {
        return 'LaLearnodexBundle:Admin:Content/UrlContent.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function visitMultipleChoiceQuestion(MultipleChoiceQuestion $content)
    {
        return 'LaLearnodexBundle:Admin:Content/MultipleChoiceQuestion.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function visitSimpleQuestion(SimpleQuestion $content)
    {
        return 'LaLearnodexBundle:Admin:Content/SimpleQuestion.html.twig';
    }
}
