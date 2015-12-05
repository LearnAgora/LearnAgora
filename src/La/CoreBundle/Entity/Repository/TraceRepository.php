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
        if (is_a($learningEntity,'La\CoreBundle\Entity\Techne')) {
            $query = $this->createQueryBuilder('trace')
                ->innerJoin('trace.outcome', 'outcome')
                ->leftJoin('outcome.learningEntity', 'action')
                ->leftJoin('action.uplinks', 'uplink1')
                ->leftJoin('uplink1.parent', 'agora')
                ->leftJoin('agora.uplinks', 'uplink2')
                ->leftJoin('uplink2.parent', 'techne')
                ->andWhere('trace.user = :userId')
                ->andWhere('techne.id = :learningEntityId')
                ->getQuery()
                ->setParameters(array(
                    'userId'           => $user->getId(),
                    'learningEntityId' => $learningEntity->getId(),
                ));
            //return $query->getSQL() . ", user_id=".$user->getId().", leId=".$learningEntity->getId();
        } else {
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
        }
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

        return $query->getOneOrNullResult();
    }

}
