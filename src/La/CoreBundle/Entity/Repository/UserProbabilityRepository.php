<?php

namespace La\CoreBundle\Entity\Repository;

use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\ORM\EntityRepository;
use La\CoreBundle\Entity\LearningEntity;
use La\CoreBundle\Entity\Outcome;
use La\CoreBundle\Entity\Profile;
use La\CoreBundle\Entity\User;
use La\CoreBundle\Entity\UserProbability;
use La\CoreBundle\Model\Probability\BayesForUserProbabilities;

class UserProbabilityRepository extends EntityRepository
{
    /*
    public function loadProbabilitiesForOld(User $user, LearningEntity $learningEntity, Outcome $outcome)
    {
        return $this->findBy(array('user' => $user, 'learningEntity' => $learningEntity));
    }
    */

    public function updateProbabilitiesFor(User $user, LearningEntity $learningEntity, Outcome $outcome)
    {
        /*
         * I want to have an array with an entry for each profile
         * Each entry is has 3 objects : profile, outcomeProbability and userProbability
         * outcomeProbability and userProbability can be null
         */
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p.id as profileId')
            ->addSelect('up as userProbability')
            ->addSelect('op.probability as outcomeProbability')
            ->from('LaCoreBundle:Profile', 'p')
            ->leftJoin('LaCoreBundle:UserProbability', 'up', 'WITH', 'p.id=up.profile AND up.user=:userId AND up.learningEntity=:learningEntityId')
            ->leftJoin('LaCoreBundle:OutcomeProbability', 'op', 'WITH', 'p.id=op.profile AND op.outcome=:outcomeId')
            ->setParameter('userId', $user->getId())
            ->setParameter('learningEntityId', $learningEntity->getId())
            ->setParameter('outcomeId', $outcome->getId());

        $query = $qb->getQuery();
        $results = $query->getResult();

        /*
         * Before the results are sent back we need to check if each profile has a userProbability and an outcomeProbability
         * Is it good to do that here?
         */

        /*
         * Algorithm to define a new userProbability:
         */
        $missingUserProbabilities = 0;
        $totalProbability = 0;
        foreach ($results as $result) {
            /* @var UserProbability $userProbability */
            $userProbability = $result['userProbability'];
            if (is_null($userProbability)) {
                $missingUserProbabilities++;
            } else {
                $totalProbability += $userProbability->getProbability();
            }
        }

        $newProbabilityValue = $missingUserProbabilities ? (1-$totalProbability)/$missingUserProbabilities : 0;
        $bayesForUserProbability = new BayesForUserProbabilities();

        foreach ($results as $key => $result) {
            $userProbability = $result['userProbability'];
            if (is_null($userProbability)) {
                /* @var Profile $profile */
                $profile = $this->getEntityManager()->getRepository('LaCoreBundle:Profile')->find($result['profileId']);

                $userProbability = new UserProbability();
                $userProbability->setProfile($profile);
                $userProbability->setUser($user);
                $userProbability->setLearningEntity($learningEntity);
                $userProbability->setProbability($newProbabilityValue);
            }
            $bayesForUserProbability->add($userProbability,$result['outcomeProbability']);
        }

        $bayesForUserProbability->updateProbabilities();

        $userProbabilities = $bayesForUserProbability->getProbabilities();
        foreach($userProbabilities as $userProbability) {
            $this->getEntityManager()->persist($userProbability);
        }
        $this->getEntityManager()->flush();

        return $userProbabilities;
    }

}