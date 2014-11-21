<?php

namespace La\CoreBundle\Entity\Repository;

use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\ORM\EntityRepository;
use La\CoreBundle\Entity\LearningEntity;
use La\CoreBundle\Entity\User;

class TraceRepository extends EntityRepository
{

    public function findAllForLearningEntity(LearningEntity $learningEntity, User $user)
    {
        $query = $this->createQueryBuilder('t')
            ->innerJoin('t.outcome', 'o')
            ->leftJoin('o.learningEntity', 'le')
            ->andWhere('t.user = :userId')
            ->andWhere('le.id = :learningEntityId')
            ->getQuery()
            ->setParameters(array(
                'userId'           => $user->getId(),
                'learningEntityId' => $learningEntity->getId(),
            ));

        return $query->getResult();
    }

    public function findLastForLearningEntity(LearningEntity $learningEntity, User $user)
    {
        $query = $this->createQueryBuilder('t')
            ->innerJoin('t.outcome', 'o')
            ->leftJoin('o.learningEntity', 'le')
            ->andWhere('t.user = :userId')
            ->andWhere('le.id = :learningEntityId')
            ->orderBy('t.createdTime', 'DESC')
            ->getQuery()
            ->setMaxResults(1)
            ->setParameters(array(
                'userId'           => $user->getId(),
                'learningEntityId' => $learningEntity->getId(),
            ));

        return $query->getSingleResult();
    }

}
