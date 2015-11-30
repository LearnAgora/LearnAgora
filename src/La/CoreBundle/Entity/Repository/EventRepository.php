<?php

namespace La\CoreBundle\Entity\Repository;

use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\ORM\EntityRepository;
use La\CoreBundle\Entity\User;

/**
 * @DI\Service
 */
class EventRepository extends EntityRepository
{
    public function loadAllFor(User $user)
    {
        $query = $this->createQueryBuilder('e')
            ->andWhere('e.user = :userId')
            ->andWhere('e.removed = 0')
            ->orderBy('e.createdOn','DESC')
            ->getQuery()
            ->setParameters(array(
                'userId'           => $user->getId(),
            ));

        return $query->getResult();
    }

    public function loadNewFor(User $user)
    {
        $query = $this->createQueryBuilder('e')
            ->andWhere('e.user = :userId')
            ->andWhere('e.seen = 0')
            ->andWhere('e.removed = 0')
            ->orderBy('e.created_on','DESC')
            ->getQuery()
            ->setParameters(array(
                'userId'           => $user->getId(),
            ));

        return $query->getResult();
    }

}