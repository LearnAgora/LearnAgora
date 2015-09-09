<?php

namespace La\CoreBundle\Entity\Repository;

use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\ORM\EntityRepository;
use La\CoreBundle\Entity\User;

class UserProbabilityEventRepository extends EntityRepository
{
    public function loadAllFor(User $user)
    {
        $query = $this->createQueryBuilder('upe')
            ->innerJoin('upe.userProbability', 'up')
            ->andWhere('up.user = :userId')
            ->andWhere('upe.removed = 0')
            ->orderBy('upe.createdOn','DESC')
            ->getQuery()
            ->setParameters(array(
                'userId'           => $user->getId(),
            ));

        return $query->getResult();
    }

    public function loadNewFor(User $user)
    {
        $query = $this->createQueryBuilder('upe')
            ->innerJoin('upe.userProbability', 'up')
            ->andWhere('up.user = :userId')
            ->andWhere('upe.seen = 0')
            ->andWhere('upe.removed = 0')
            ->orderBy('upe.created_on','DESC')
            ->getQuery()
            ->setParameters(array(
                'userId'           => $user->getId(),
            ));

        return $query->getResult();
    }


}