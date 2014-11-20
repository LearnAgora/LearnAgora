<?php

namespace La\CoreBundle\Entity\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\ORM\EntityRepository;
use La\CoreBundle\Entity\User;
use La\CoreBundle\Entity\LearningEntity;
use La\CoreBundle\Entity\Outcome;
use La\CoreBundle\Entity\Trace;
use Symfony\Component\Security\Core\SecurityContextInterface;

class ActionRepository extends EntityRepository
{
    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * @var ObjectRepository
     */
    private $traceRepository;

    /**
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext
     * @param ObjectRepository $traceRepository
     *
     * @DI\InjectParams({
     *  "securityContext" = @DI\Inject("security.context"),
     *  "traceRepository" = @DI\Inject("la_core.repository.trace")
     * })
     */
    public function __construct(SecurityContextInterface $securityContext, ObjectRepository $traceRepository)
    {
        $this->securityContext = $securityContext;
        $this->traceRepository = $traceRepository;
    }

    public function findOneOrNullUnvisitedActions()
    {
        $user = $this->securityContext->getToken()->getUser();
        $unvisitedLearningEntities = array();

        $actions = $this->findAll();
        if (count($actions))
        {
            shuffle($actions);
            /* @var LearningEntity $learningEntity */
            foreach ($actions as $learningEntity)
            {
                $hasTrace = false;

                /** @var $outcome Outcome */
                foreach ($learningEntity->getOutcomes() as $outcome)
                {
                    $trace = $this->traceRepository->findOneBy( array('user' => $user,'Outcome' => $outcome)   );
                    if ($trace)
                    {
                        $hasTrace = true;
                    }
                }

                if (!$hasTrace)
                {
                    $unvisitedLearningEntities[] = $learningEntity;
                }
            }

        }

        if (count($unvisitedLearningEntities))
        {
            return $unvisitedLearningEntities[0];
        } else
        {
            return null;
        }
    }

    public function findUnvisited2Actions()
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

    public function findOneOrNullPostponedActions()
    {
        $user = $this->securityContext->getToken()->getUser();
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
