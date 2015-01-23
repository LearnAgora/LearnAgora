<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\CoreBundle\Model\Goal;

use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\User;
use La\CoreBundle\Visitor\VisitorInterface;
use La\LearnodexBundle\Model\Visitor\Goal\GetAffinityVisitor;
use La\LearnodexBundle\Model\Visitor\Goal\GetNameVisitor;


abstract class GoalBase
{

    abstract public function accept(VisitorInterface $visitor);

    public function getName() {
        $getNameVisitor = new GetNameVisitor();
        return $this->accept($getNameVisitor);
    }

    public function getAffinity(User $user) {
        $getAffinityVisitor = new GetAffinityVisitor($user);
        return $this->accept($getAffinityVisitor);
    }

}
