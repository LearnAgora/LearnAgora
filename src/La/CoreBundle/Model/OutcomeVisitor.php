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

class OutcomeVisitor implements LearningEntityVisitorInterface
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
        $outcomes = array();
        return 'i am an agora';
    }

    /**
     * {@inheritdoc}
     */
    public function visitObjective(Objective $objective)
    {
        return 'i am an objective';
    }

    /**
     * {@inheritdoc}
     */
    public function visitAction(Action $action)
    {
        return 'i am an action';
    }
} 