<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\LearnodexBundle\Model\Visitor;

use La\CoreBundle\Entity\AffinityOutcome;
use La\CoreBundle\Entity\AnswerOutcome;
use La\CoreBundle\Entity\ButtonOutcome;
use La\CoreBundle\Entity\UrlOutcome;
use La\CoreBundle\Visitor\AffinityOutcomeVisitorInterface;
use La\CoreBundle\Visitor\AnswerOutcomeVisitorInterface;
use La\CoreBundle\Visitor\ButtonOutcomeVisitorInterface;
use La\CoreBundle\Visitor\UrlOutcomeVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;


class GetOutcomeIncludeTwigVisitor implements
    VisitorInterface,
    AffinityOutcomeVisitorInterface,
    AnswerOutcomeVisitorInterface,
    ButtonOutcomeVisitorInterface,
    UrlOutcomeVisitorInterface
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
}