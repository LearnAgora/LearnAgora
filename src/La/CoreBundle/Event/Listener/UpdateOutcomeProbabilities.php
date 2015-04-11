<?php

namespace La\CoreBundle\Event\Listener;

use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\Common\Persistence\ObjectManager;
use La\CoreBundle\Entity\Outcome;
use La\CoreBundle\Entity\OutcomeProbability;
use La\CoreBundle\Entity\Profile;
use La\CoreBundle\Event\LearningEntityChangedEvent;
use La\CoreBundle\Event\MissingOutcomeProbabilityEvent;
use La\CoreBundle\Events;
use La\CoreBundle\Visitor\Outcome\GetDefaultOutcomeProbabilityVisitor;

/**
 * @DI\Service
 */
class UpdateOutcomeProbabilities
{
    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * Constructor.
     *
     * @param ObjectManager $entityManager
     *
     * @DI\InjectParams({
     *  "entityManager" = @DI\Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(ObjectManager $entityManager) {
        $this->entityManager = $entityManager;
   }


    /**
     * @DI\Observe(Events::MISSING_OUTCOME_PROBABILITY)
     *
     * @param MissingOutcomeProbabilityEvent $missingOutcomeProbabilityEvent
     */
    public function onResult(MissingOutcomeProbabilityEvent $missingOutcomeProbabilityEvent)
    {
        $outcomeWithMissingProbability = $missingOutcomeProbabilityEvent->getOutcome();
        $outcomeProbabilityCollection = $missingOutcomeProbabilityEvent->getOutcomeProbabilityCollection();

        $profiles = $outcomeProbabilityCollection->getProfiles();
        $outcomes = $outcomeWithMissingProbability->getLearningEntity()->getOutcomes();

        $numAnswerOutcomes = 0;
        foreach ($outcomes as $outcome) {
            /* @var Outcome $outcome */
            if (is_a($outcome,'La\CoreBundle\Entity\AnswerOutcome')) {
                $numAnswerOutcomes++;
            }
        }

        foreach ($profiles as $profile) {
            /* @var Profile $profile */
            $getDefaultOutcomeProbabilityVisitor = new GetDefaultOutcomeProbabilityVisitor($profile, $numAnswerOutcomes);
            foreach ($outcomes as $outcome) {
                /* @var Outcome $outcome */
                $outcomeProbability = $this->entityManager->getRepository('LaCoreBundle:OutcomeProbability')->findOneBy(array('outcome'=>$outcome, 'profile'=>$profile));
                if (is_null($outcomeProbability)) {
                    $outcomeProbability = new OutcomeProbability();
                    $outcomeProbability->setProfile($profile);
                    $outcomeProbability->setOutcome($outcome);
                }
                $outcomeProbability->setProbability($outcome->accept($getDefaultOutcomeProbabilityVisitor));

                $this->entityManager->persist($outcomeProbability);

                if ($outcome->getId() == $outcomeWithMissingProbability->getId()) {
                    $outcomeProbabilityCollection->setOutcomeProbabilityForProfile($profile,$outcomeProbability);
                }
            }
        }

        $this->entityManager->flush();

    }


    /**
     * @DI\Observe(Events::LEARNING_ENTITY_CHANGED)
     *
     * @param LearningEntityChangedEvent $learningEntityChangedEvent
     */
    public function onLearningEntityChangedEvent(LearningEntityChangedEvent $learningEntityChangedEvent)
    {
        $learningEntity = $learningEntityChangedEvent->getLearningEntity();

        $profiles = $this->entityManager->getRepository('LaCoreBundle:Profile')->findAll();
        $outcomes = $learningEntity->getOutcomes();

        $numAnswerOutcomes = 0;
        foreach ($outcomes as $outcome) {
            /* @var Outcome $outcome */
            if (is_a($outcome,'La\CoreBundle\Entity\AnswerOutcome')) {
                $numAnswerOutcomes++;
            }
        }

        foreach ($profiles as $profile) {
            /* @var Profile $profile */
            $getDefaultOutcomeProbabilityVisitor = new GetDefaultOutcomeProbabilityVisitor($profile, $numAnswerOutcomes);
            foreach ($outcomes as $outcome) {
                /* @var Outcome $outcome */
                $outcomeProbability = $this->entityManager->getRepository('LaCoreBundle:OutcomeProbability')->findOneBy(array('outcome'=>$outcome, 'profile'=>$profile));
                if (is_null($outcomeProbability)) {
                    $outcomeProbability = new OutcomeProbability();
                    $outcomeProbability->setProfile($profile);
                    $outcomeProbability->setOutcome($outcome);
                }
                $outcomeProbability->setProbability($outcome->accept($getDefaultOutcomeProbabilityVisitor));

                $this->entityManager->persist($outcomeProbability);
           }
        }

        $this->entityManager->flush();

    }

}
