<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\CoreBundle\Model;


use La\CoreBundle\Entity\Action;
use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\Objective;

class PossibleOutcomeVisitor implements LearningEntityVisitorInterface
{
    /**
     * {@inheritdoc}
     */
    public function visitAgora(Agora $agora)
    {
        /*
        possible outcomes of an agora are :
        - you reach x% affinity
        - you
        */
        $outcome = new AffinityOutcome();
        $outcomes = array($outcome);
        return $outcomes;
    }

    /**
     * {@inheritdoc}
     */
    public function visitObjective(Objective $objective)
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function visitAction(Action $action)
    {
        return array();
    }
} 