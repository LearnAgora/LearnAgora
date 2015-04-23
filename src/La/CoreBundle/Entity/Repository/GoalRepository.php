<?php

namespace La\CoreBundle\Entity\Repository;

use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\ORM\EntityRepository;
use La\CoreBundle\Entity\User;

class GoalRepository extends EntityRepository
{
    public function resetActiveGoalsFor(User $user) {
        $this->createQueryBuilder('')
            ->update('LaCoreBundle:Goal','g')
            ->set('g.active',':active')
            ->where('g.user=:user')
            ->setParameters(array(
                'active' => 0,
                'user' => $user,
            ))
            ->getQuery()
            ->execute();
    }
}
