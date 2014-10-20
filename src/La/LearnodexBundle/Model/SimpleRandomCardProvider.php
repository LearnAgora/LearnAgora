<?php

namespace La\LearnodexBundle\Model;

use Doctrine\Common\Persistence\ObjectRepository;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\User;
use La\CoreBundle\Entity\Outcome;
use La\CoreBundle\Entity\Result;
use La\CoreBundle\Entity\Trace;
use La\LearnodexBundle\Model\Exception\CardNotFoundException;

/**
 * This random card provider naively fetches all the cards from the
 * repository, just to return the one from the whole set.
 *
 */
class SimpleRandomCardProvider implements RandomCardProviderInterface
{
    /**
     * @var ObjectRepository
     */
    private $learningEntityRepository;

    /**
     * @var User
     */
    private $user;

    /**
     * Constructor.
     *
     * @param User $user
     * @param ObjectRepository $learningEntityRepository
     *
     */
    public function __construct(User $user, ObjectRepository $learningEntityRepository)
    {
        $this->user = $user;
        $this->learningEntityRepository = $learningEntityRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getCard()
    {
        $learningEntities = $this->learningEntityRepository->findAll();

        $unvisitedLearningEntities = array();
        $postponedLearningEntities = array();
        if (count($learningEntities)) {
            shuffle($learningEntities);
            foreach ($learningEntities as $learningEntity) {
                $hasTrace = false;
                $hasLater = false;
                $outcomes = $learningEntity->getOutcomes();
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
                                if ($trace->getUser()->getId() == $this->user->getId()) {
                                    $userTraces[] = $trace;
                                }
                            }
                        }
                    }
                }
                if (count($userTraces)) {
                    $hasTrace = true;
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

                if ($hasTrace) {
                    if ($hasLater) {
                        $postponedLearningEntities[] = $learningEntity;
                    }
                } else {
                    $unvisitedLearningEntities[] = $learningEntity;
                }
            }

            $selectedLearningEntity = null;

            if (count($unvisitedLearningEntities)) {
                $selectedLearningEntity = $unvisitedLearningEntities[0];
            } elseif (count($postponedLearningEntities)) {
                $selectedLearningEntity = $postponedLearningEntities[0];
            }

            if (!is_null($selectedLearningEntity)) {
                return new Card($selectedLearningEntity);
            } else {
                return null;
            }

        }

        throw new CardNotFoundException();
    }
}
