<?php

namespace La\CoreBundle\Entity\Repository;

use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\ORM\EntityRepository;
use La\CoreBundle\Entity\User;

class AgoraBaseRepository extends EntityRepository
{

    public function findProbabilitiesForUser(User $user) {
        $query = $this->createQueryBuilder('a')
            ->innerJoin('a.userProbabilities', 'u')
            ->where('u.user = :userId')
            ->getQuery()
            ->setParameters(array(
                'userId'           => $user->getId(),
            ));

        return $query->getResult();
    }
}
