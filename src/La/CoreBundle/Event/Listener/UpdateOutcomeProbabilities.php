<?php

namespace La\CoreBundle\Event\Listener;

use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\Common\Persistence\ObjectManager;
use La\CoreBundle\Entity\Outcome;
use La\CoreBundle\Entity\OutcomeProbability;
use La\CoreBundle\Entity\Profile;
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
        $bayesData = $missingOutcomeProbabilityEvent->getBayesData();

        $profiles = $this->entityManager->getRepository('LaCoreBundle:Profile')->findAll();
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
                    $bayesData->setOutcomeProbability($profile->getId(),$outcomeProbability->getProbability());
                }
            }
        }

        $this->entityManager->flush();

    }

}