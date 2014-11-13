<?php

namespace La\CoreBundle\Entity\Repository;

use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\ORM\EntityRepository;
use La\CoreBundle\Entity\User;

class ActionRepository extends EntityRepository
{
    public function findOneOrNullUnvisitedActions(User $user)
    {
        $unvisitedLearningEntities = array();

        $actions = $this->findAll();
        if (count($actions)) {
            shuffle($actions);
            foreach ($actions as $learningEntity) {
                $hasTrace = false;
                $outcomes = $learningEntity->getOutcomes();
                $userTraces = array();
                /** @var $outcome Outcome */
                foreach ($outcomes as $outcome) {
                    $results = $outcome->getResults();
                    /** @var $result Result */
                    foreach ($results as $result) {
                        if (is_a($result, 'La\CoreBundle\Entity\AffinityResult')) {
                            $traces = $outcome->getTraces();
                            /** @var $trace Trace */
                            foreach ($traces as $trace) {
                                if ($trace->getUser()->getId() == $user->getId()) {
                                    $userTraces[] = $trace;
                                }
                            }
                        }
                    }
                }
                if (count($userTraces)) {
                    $hasTrace = true;
                }

                if (!$hasTrace) {
                    $unvisitedLearningEntities[] = $learningEntity;
                }
            }

        }

        if (count($unvisitedLearningEntities)) {
            return $unvisitedLearningEntities[0];
        } else {
            return null;
        }
    }

    public function findUnvisited2Actions(User $user)
    {
        //this is how it should be but i can't make the query work
        $query = $this->createQueryBuilder('a')
            ->innerJoin('a.outcomes', 'o')
            ->leftJoin('o.traces', 't', 'WITH', 't.user = :userId')
            ->andWhere('t.id IS NULL')
            //->andWhere('t.user = :userId')
            ->getQuery()
            ->setParameters(array(
//                'userId' => $user->getId(),
                'userId' => 1,
            ));

        //return $query->getSQL();
        return $query->getResult();
    }

    public function findOneOrNullPostponedActions(User $user) {
        $postponedActions = array();

        $actions = $this->findAll();

        if (count($actions) == 0) {
            return null;
        }

        foreach ($actions as $action) {
            $hasLater = false;
            $outcomes = $action->getOutcomes();
            $userTraces = array();
            /** @var $outcome Outcome */
            foreach ($outcomes as $outcome) {
                $results = $outcome->getResults();
                /** @var $result Result */
                foreach ($results as $result) {
                    if (is_a($result,'La\CoreBundle\Entity\AffinityResult')) {
                        $traces = $outcome->getTraces();
                        /** @var $trace Trace */
                        foreach ($traces as $trace) {
                            if ($trace->getUser()->getId() == $user->getId()) {
                                $userTraces[] = $trace;
                            }
                        }
                    }
                }
            }
            if (count($userTraces)) {
                //find the last trace
                /** @var $lastTrace Trace */
                $lastTrace = null;
                $lastTimestamp = 0;
                foreach ($userTraces as $trace) {
                    $timestamp = strtotime($trace->getCreatedTime()->format('Y-m-d H:i:s'));
                    if ($timestamp > $lastTimestamp) {
                        $lastTimestamp = $timestamp;
                        $lastTrace = $trace;
                    }
                }
                if (is_a($lastTrace->getOutcome(),'La\CoreBundle\Entity\ButtonOutcome')) {
                    if ($lastTrace->getOutcome()->getCaption() == 'LATER') {
                        $hasLater = true;
                    }
                }
            }

            if ($hasLater) {
                $postponedActions[] = $action;
            }
        }

        $postponedAction = null;

        if (count($postponedActions)) {
            return $postponedActions[0];
        } else {
            return null;
        }


    }

    public function findOneOrNullPostponedActions_tobe(User $user) {
        $query = $this->createQueryBuilder('a')
            ->innerJoin('a.outcomes', 'o')
            ->leftJoin('o.traces', 't', 'WITH', 't.user = :userId')
            ->andWhere('o.caption = :caption')
            ->andWhere('t.user = :userId')
            ->getQuery()
            ->setParameters(array(
//                'userId' => $user->getId(),
                'userId' => 1,
                'subject' => 'button',
                'caption' => 'LATER',
            ));

        die(var_dump($query->getResult()));
        return $query->getResult();
    }


}
