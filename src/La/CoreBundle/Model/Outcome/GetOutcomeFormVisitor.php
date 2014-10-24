<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\CoreBundle\Model\Outcome;

use La\CoreBundle\Entity\AffinityOutcome;
use La\CoreBundle\Entity\AnswerOutcome;
use La\CoreBundle\Forms\AffinityOutcomeType;
use La\CoreBundle\Forms\AnswerOutcomeType;
use La\CoreBundle\Visitor\AffinityOutcomeVisitorInterface;
use La\CoreBundle\Visitor\AnswerOutcomeVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

class GetOutcomeFormVisitor implements VisitorInterface, AffinityOutcomeVisitorInterface, AnswerOutcomeVisitorInterface
{
    /**
     * {@inheritdocm
     */
    public function visitAffinityOutcome(AffinityOutcome $outcome)
    {
        return new AffinityOutcomeType();
    }

    /**
     * {@inheritdoc}
     */
    public function visitAnswerOutcome(AnswerOutcome $outcome)
    {
        return new AnswerOutcomeType();
    }
}
