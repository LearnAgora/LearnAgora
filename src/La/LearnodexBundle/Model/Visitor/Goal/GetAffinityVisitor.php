<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\LearnodexBundle\Model\Visitor\Goal;

use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Affinity;
use La\CoreBundle\Entity\AgoraGoal;
use La\CoreBundle\Entity\PersonaGoal;
use La\CoreBundle\Entity\User;
use La\CoreBundle\Visitor\AgoraGoalVisitorInterface;
use La\CoreBundle\Visitor\PersonaGoalVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

class GetAffinityVisitor implements
    VisitorInterface,
    AgoraGoalVisitorInterface,
    PersonaGoalVisitorInterface
{
    private $user;

    public function __construct(User $user) {
        $this->user = $user;
    }
    /**
     * {@inheritdoc}
     */
    public function visitAgoraGoal(AgoraGoal $goal)
    {
        $result = 0;
        foreach ( $goal->getAgora()->getAffinities() as $affinity) {
            /* @var Affinity $affinity */
            if ($affinity->getUser()->getId() == $this->user->getId()) {
                $result = $affinity->getValue();
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function visitPersonaGoal(PersonaGoal $goal)
    {
        $result = 0;
        //foreach ($goal->getPersona()->get)
        return $result;
    }

}
