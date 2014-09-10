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
use La\CoreBundle\Visitor\AffinityOutcomeVisitorInterface;
use La\CoreBundle\Visitor\AnswerOutcomeVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;


class GetOutcomeIncludeTwigVisitor implements
    VisitorInterface,
    AffinityOutcomeVisitorInterface,
    AnswerOutcomeVisitorInterface
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

}