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
class WeightedRandomCardProvider implements RandomCardProviderInterface
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

        if (count($learningEntities)) {
            shuffle($learningEntities);

            $distribution = array();
            $weightedIndex = 0;
            foreach ($learningEntities as $learningEntity) {

                $hasTrace = false;
                $hasDiscarded = false;
                $hasLater = false;

                $outcomes = $learningEntity->getOutcomes();
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
                                    $hasTrace = true;
                                    if (is_a($outcome,'La\CoreBundle\Entity\ButtonOutcome')) {
                                        if ($outcome->getCaption() == 'LATER') {
                                            $hasLater = true;
                                        }
                                        if ($outcome->getCaption() == 'DISCARD') {
                                            $hasDiscarded = true;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                $chance = 1;

                if ($hasTrace) {
                    $chance = 0.1;
                    if ($hasDiscarded) {
                        $chance = 0;
                    }
                    if ($hasLater) {
                        $chance = 0.2;
                    }
                }

                $weightedIndex += $chance;
                $distribution[] = array(
                    'index' => $weightedIndex,
                    'learningEntity' => $learningEntity,
                );
            }

            $randomNumber = $weightedIndex * mt_rand() / mt_getrandmax();
            $selectedLearningEntity = null;
            foreach ($distribution as $candidate) {
                if ($randomNumber < $candidate['index']) {
                    $selectedLearningEntity = $candidate['learningEntity'];
                    break;
                }
            }
            if (!is_null($selectedLearningEntity)) {
                return new Card($selectedLearningEntity);
            } else {
                throw new CardNotFoundException();
            }

        }

        throw new CardNotFoundException();
    }
}
