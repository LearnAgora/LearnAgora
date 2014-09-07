<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\CoreBundle\Model;


use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Visitor\AgoraVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

class PossibleOutcomeVisitor implements VisitorInterface, AgoraVisitorInterface
{
    /**
     * {@inheritdoc}
     */
    public function visitAgora(Agora $agora)
    {
        /*
        possible outcomes of an agora are :
        - you reach x% affinity
        */
        $outcome = new AffinityOutcome();
        $outcomes = array($outcome);
        return $outcomes;
    }
} 