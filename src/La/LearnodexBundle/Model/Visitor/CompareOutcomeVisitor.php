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


class CompareOutcomeVisitor implements
    VisitorInterface,
    AffinityOutcomeVisitorInterface,
    AnswerOutcomeVisitorInterface,
    ButtonOutcomeVisitorInterface,
    UrlOutcomeVisitorInterface
{
    private $referenceOutcome = null;

    public function __construct($referenceOutcome) {
        $this->referenceOutcome = $referenceOutcome;
    }
    /**
     * {@inheritdoc}
     */
    public function visitAffinityOutcome(AffinityOutcome $outcome)
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function visitAnswerOutcome(AnswerOutcome $outcome)
    {
        $isEqual = true;
        if ($outcome->getAnswer()->getId() != $this->referenceOutcome->getAnswer()->getId()) {
            $isEqual = false;
        }
        if ($outcome->getSelected() != $this->referenceOutcome->getSelected()) {
            $isEqual = false;
        }
        return $isEqual;
    }

    /**
     * {@inheritdoc}
     */
    public function visitButtonOutcome(ButtonOutcome $outcome)
    {
        $isEqual = true;
        if ($outcome->getCaption() != $this->referenceOutcome->getCaption()) {
            $isEqual = false;
        }
        return $isEqual;
    }

    /**
     * {@inheritdoc}
     */
    public function visitUrlOutcome(UrlOutcome $outcome)
    {
        return true;
    }
}