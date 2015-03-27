<?php

namespace La\CoreBundle\Visitor;

use La\CoreBundle\Entity\AgoraGoal;

interface AgoraGoalVisitorInterface
{
    /**
     * @param AgoraGoal $goal
     **/
    public function visitAgoraGoal(AgoraGoal $goal);
}
