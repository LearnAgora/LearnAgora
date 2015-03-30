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
        $userProbabilityCollection = $missingUserProbabilityEvent->getUserProbabilityCollection();

        $defaultUserProbabilityValue = $userProbabilityCollection->getDefaultUserProbabilityValue();

        foreach ($userProbabilityCollection->getProfiles() as $profile) {
            /* @var Profile $profile */
            if (is_null($userProbabilityCollection->getUserProbabilityForProfile($profile)))
            {
                $userProbability = new UserProbability();
                $userProbability->setProfile($profile);
                $userProbability->setUser($user);
                $userProbability->setLearningEntity($agora);
                $userProbability->setProbability($defaultUserProbabilityValue);

                $userProbabilityCollection->setUserProbabilityForProfile($profile,$userProbability);
            }
        }

        $userProbabilityCollection->normalizeUserProbabilities();

        foreach ($userProbabilityCollection->getUserProbabilities() as $userProbability) {
            $this->entityManager->persist($userProbability);
        }
        $this->entityManager->flush();
    }
}
