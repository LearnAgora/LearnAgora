<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\CoreBundle\Model;


use La\CoreBundle\Entity\AffinityOutcome;
use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\Action;
use La\CoreBundle\Entity\Objective;
use La\CoreBundle\Model\Content\PossibleOutcomeActionVisitor;
use La\CoreBundle\Visitor\ActionVisitorInterface;
use La\CoreBundle\Visitor\AgoraVisitorInterface;
use La\CoreBundle\Visitor\ObjectiveVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

class PossibleOutcomeVisitor implements VisitorInterface, AgoraVisitorInterface, ObjectiveVisitorInterface, ActionVisitorInterface
{
    /**
     * {@inheritdoc}
     */
    public function visitAgora(Agora $agora)
    {
        $outcome = new AffinityOutcome();
        $outcomes = array($outcome);
        return $outcomes;
    }

    /**
     * {@inheritdoc}
     */
    public function visitObjective(Objective $objective)
    {
        $outcomes = array();
        return $outcomes;
    }
    /**
     * {@inheritdoc}
     */
    public function visitAction(Action $action)
    {
        $possibleOutcomeActionVisitor = new PossibleOutcomeActionVisitor();
        return $action->getContent()->accept($possibleOutcomeActionVisitor);
    }

} 