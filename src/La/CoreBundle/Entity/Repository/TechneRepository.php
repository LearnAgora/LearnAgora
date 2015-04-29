<?php

namespace La\CoreBundle\Entity\Repository;

use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\ORM\EntityRepository;
use La\CoreBundle\Entity\User;

class TechneRepository extends EntityRepository
{
    public function findProbabilitiesForUser(User $user) {
        $query = $this->createQueryBuilder('a')
            ->addSelect('u')
            ->leftJoin('a.userProbabilities', 'u')
            ->where('u.user = :userId')
            ->getQuery()
            ->setParameters(array(
                'userId'           => $user->getId(),
            ));

        return $query->getResult();
    }

}
