<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\CoreBundle\Model\LearningEntity;

use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\Action;
use La\CoreBundle\Entity\Objective;
use La\CoreBundle\Model\Content\TwigOutcomeContentVisitor;
use La\CoreBundle\Visitor\ActionVisitorInterface;
use La\CoreBundle\Visitor\AgoraVisitorInterface;
use La\CoreBundle\Visitor\ObjectiveVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

class TwigOutcomeVisitor implements VisitorInterface, AgoraVisitorInterface, ObjectiveVisitorInterface, ActionVisitorInterface
{
    /**
     * {@inheritdocm
     */
    public function visitAgora(Agora $learningEntity)
    {
        return 'LaLearnodexBundle:Admin:HtmlOutcome.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function visitObjective(Objective $learningEntity)
    {
        return 'LaLearnodexBundle:Admin:UrlOutcome.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function visitAction(Action $learningEntity)
    {
        $content = $learningEntity->getContent();
        $twigOutcomeContentVisitor = new TwigOutcomeContentVisitor();
        return $content->accept($twigOutcomeContentVisitor);
    }

    /**
     * {@inheritdoc}
     */
    public function visitQuizContent(QuizContent $content)
    {
        return 'LaLearnodexBundle:Admin:QuizOutcome.html.twig';
    }
}
