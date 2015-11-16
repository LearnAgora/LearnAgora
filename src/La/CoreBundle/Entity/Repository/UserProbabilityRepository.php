<?php

namespace La\CoreBundle\Entity\Repository;

use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\ORM\EntityRepository;
use La\CoreBundle\Entity\LearningEntity;
use La\CoreBundle\Entity\User;

class UserProbabilityRepository extends EntityRepository
{
    public function getUserProbabilities(User $user, LearningEntity $learningEntity) {
        $query = $this->createQueryBuilder('up')
            ->andWhere('up.user = :userId')
            ->andWhere('up.learningEntity = :learningEntityId')
            ->getQuery()
            ->setParameters(array(
                'userId'           => $user->getId(),
                'learningEntityId' => $learningEntity->getId(),
            ));

        return $query->getResult();

    }


}