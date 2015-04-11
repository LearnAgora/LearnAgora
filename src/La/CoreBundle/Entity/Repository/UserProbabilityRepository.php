<?php

namespace La\CoreBundle\Entity\Repository;

use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\ORM\EntityRepository;

class UserProbabilityRepository extends EntityRepository
{
/*
    public function loadBayesDataFor(User $user, LearningEntity $learningEntity, Outcome $outcome)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p.id as '.BayesData::PROFILE_ID)
            ->addSelect('up as '.BayesData::USER_PROBABILITY)
            ->addSelect('op.probability as '.BayesData::OUTCOME_PROBABILITY_VALUE)
            ->from('LaCoreBundle:Profile', 'p')
            ->leftJoin('LaCoreBundle:UserProbability', 'up', 'WITH', 'p.id=up.profile AND up.user=:userId AND up.learningEntity=:learningEntityId')
            ->leftJoin('LaCoreBundle:OutcomeProbability', 'op', 'WITH', 'p.id=op.profile AND op.outcome=:outcomeId')
            ->setParameter('userId', $user->getId())
            ->setParameter('learningEntityId', $learningEntity->getId())
            ->setParameter('outcomeId', $outcome->getId());

        return new BayesData($qb->getQuery()->getResult());
    }
*/


}