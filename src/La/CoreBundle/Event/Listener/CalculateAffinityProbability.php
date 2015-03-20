<?php

namespace La\CoreBundle\Event\Listener;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Affinity;
use La\CoreBundle\Entity\OutcomeProbability;
use La\CoreBundle\Entity\Trace;
use La\CoreBundle\Entity\User;
use La\CoreBundle\Entity\UserProbability;
use La\CoreBundle\Event\TraceEvent;
use La\CoreBundle\Events;

/**
 * @DI\Service
 */
class CalculateAffinityProbability
{

    /**
     * @var ObjectRepository
     */
    private $userProbabilityRepository;

    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * Constructor.
     *
     * @param ObjectRepository $userProbabilityRepository
     * @param ObjectManager $entityManager

     *
     * @DI\InjectParams({
     *  "userProbabilityRepository" = @DI\Inject("la_core.repository.userProbability"),
     *  "entityManager" = @DI\Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(ObjectRepository $userProbabilityRepository, ObjectManager $entityManager) {
        $this->userProbabilityRepository = $userProbabilityRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @DI\Observe(Events::TRACE_CREATED)
     *
     * @param TraceEvent $traceEvent
     */
    public function onResult(TraceEvent $traceEvent)
    {
        /* @var Trace $trace */
        $trace = $traceEvent->getTrace();
        /* @var User $user */
        $user = $trace->getUser();

        $outcome = $trace->getOutcome();
        $learningEntity = $outcome->getLearningEntity();
        $parents = $learningEntity->getUplinks();
        foreach ($parents as $parent) {
            $agora = $parent->getParent();

            //load probabilities for this user
            $userProbabilities = $this->userProbabilityRepository->updateProbabilitiesFor($user, $agora, $outcome);

            $maxProbability = 0;
            $matchingProfile = null;

            foreach ($userProbabilities as $userProbability) {
                /* @var UserProbability $userProbability */
                if ($userProbability->getProbability() > $maxProbability) {
                            $maxProbability = $userProbability->getProbability();
                            $matchingProfile = $userProbability->getProfile();
                 }
            }

            /* @var Affinity $affinity */
            $affinity = $this->entityManager->getRepository('LaCoreBundle:Affinity')->findOneBy(array('user'=>$user,'agora'=>$agora));
            $affinity->setValue(100*$maxProbability);
            $affinity->setProfile($matchingProfile);
            $this->entityManager->persist($affinity);
        }



        $this->entityManager->flush();
    }
}
