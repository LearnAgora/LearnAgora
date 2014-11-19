<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\LearnodexBundle\Model\Visitor;

use La\CoreBundle\Entity\AffinityOutcome;
use La\CoreBundle\Entity\AffinityResult;
use La\CoreBundle\Entity\AnswerOutcome;
use La\CoreBundle\Entity\ButtonOutcome;
use La\CoreBundle\Entity\ProgressResult;
use La\CoreBundle\Entity\UrlOutcome;
use La\CoreBundle\Visitor\AffinityOutcomeVisitorInterface;
use La\CoreBundle\Visitor\AffinityResultVisitorInterface;
use La\CoreBundle\Visitor\AnswerOutcomeVisitorInterface;
use La\CoreBundle\Visitor\ButtonOutcomeVisitorInterface;
use La\CoreBundle\Visitor\ProgressResultVisitorInterface;
use La\CoreBundle\Visitor\UrlOutcomeVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;


class GetIncludeTwigVisitor implements
    VisitorInterface,
    AffinityOutcomeVisitorInterface,
    AnswerOutcomeVisitorInterface,
    ButtonOutcomeVisitorInterface,
    UrlOutcomeVisitorInterface,
    AffinityResultVisitorInterface,
    ProgressResultVisitorInterface
{
    /**
     * {@inheritdoc}
     */
    public function visitAffinityOutcome(AffinityOutcome $outcome)
    {
        return 'LaLearnodexBundle:Admin:Outcome/Include/AffinityOutcome.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function visitAnswerOutcome(AnswerOutcome $outcome)
    {
        return 'LaLearnodexBundle:Admin:Outcome/Include/AnswerOutcome.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function visitButtonOutcome(ButtonOutcome $outcome)
    {
        return 'LaLearnodexBundle:Admin:Outcome/Include/ButtonOutcome.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function visitUrlOutcome(UrlOutcome $outcome)
    {
        return 'LaLearnodexBundle:Admin:Outcome/Include/UrlOutcome.html.twig';
    }
    /**
     * {@inheritdoc}
     */
    public function visitAffinityResult(AffinityResult $result)
    {
        return 'LaLearnodexBundle:Admin:Result/Include/AffinityResult.html.twig';
    }
    /**
     * {@inheritdoc}
     */
    public function visitProgressResult(ProgressResult $result)
    {
        return 'LaLearnodexBundle:Admin:Result/Include/ProgressResult.html.twig';
    }
}
