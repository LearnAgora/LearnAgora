<?php

namespace La\CoreBundle\Event\Listener;

use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\Common\Persistence\ObjectManager;
use La\CoreBundle\Entity\Profile;
use La\CoreBundle\Entity\UserProbability;
use La\CoreBundle\Event\MissingUserProbabilityEvent;
use La\CoreBundle\Events;

/**
 * @DI\Service
 */
class UpdateUserProbabilities
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
     * @DI\Observe(Events::MISSING_USER_PROBABILITY)
     *
     * @param MissingUserProbabilityEvent $missingUserProbabilityEvent
     */
    public function onResult(MissingUserProbabilityEvent $missingUserProbabilityEvent)
    {
        $user = $missingUserProbabilityEvent->getUser();
        $agora = $missingUserProbabilityEvent->getAgora();
        $bayesData = $missingUserProbabilityEvent->getBayesData();

        $currentUserProbabilities = $bayesData->getNumProfilesWithUserProbability();
        $newUserProbabilityValue = $currentUserProbabilities ? 1/$currentUserProbabilities : 1;

        $profilesWithMissingUserProbability = $bayesData->getProfilesWithNullUserProbability();

        foreach ($profilesWithMissingUserProbability as $profileId) {
            /* @var Profile $profile */
            $profile = $this->entityManager->getRepository('LaCoreBundle:Profile')->find($profileId);

            $userProbability = new UserProbability();
            $userProbability->setProfile($profile);
            $userProbability->setUser($user);
            $userProbability->setLearningEntity($agora);
            $userProbability->setProbability($newUserProbabilityValue);

            $bayesData->setUserProbability($profileId,$userProbability);
        }

        $bayesData->normalizeUserProbabilities();

        foreach ($bayesData->getUserProbabilities() as $userProbability) {
            $this->entityManager->persist($userProbability);
        }

        $this->entityManager->flush();

    }
}
