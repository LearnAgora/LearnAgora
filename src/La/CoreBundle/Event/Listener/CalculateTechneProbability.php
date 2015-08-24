<?php

namespace La\CoreBundle\Event\Listener;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Repository\ProfileRepository;
use La\CoreBundle\Entity\Repository\UserProbabilityRepository;
use La\CoreBundle\Entity\Uplink;
use La\CoreBundle\Entity\UserProbability;
use La\CoreBundle\Event\UserProbabilityUpdatedEvent;
use La\CoreBundle\Events;
use La\CoreBundle\Model\Probability\UserProbabilityTrigger;


/**
 * @DI\Service
 */
class CalculateTechneProbability
{
    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * @var ProfileRepository
     */
    private $profileRepository;

    /**
     * @var UserProbabilityRepository
     */
    private $userProbabilityRepository;

    /**
     * Constructor.
     *
     * @param ObjectManager $entityManager
     * @param ProfileRepository $profileRepository
     * @param UserProbabilityRepository $userProbabilityRepository
     * @param UserProbabilityTrigger $userProbabilityTrigger
     *
     * @DI\InjectParams({
     *  "entityManager" = @DI\Inject("doctrine.orm.entity_manager"),
     *  "profileRepository" = @DI\Inject("la_core.repository.profile"),
     *  "userProbabilityRepository" = @DI\Inject("la_core.repository.user_probability"),
     *  "userProbabilityTrigger" = @DI\Inject("la.core_bundle.model.probability.user_probability_trigger")
     * })
     */
    public function __construct(ObjectManager $entityManager, ProfileRepository $profileRepository, UserProbabilityRepository $userProbabilityRepository, UserProbabilityTrigger $userProbabilityTrigger)
    {
        $this->entityManager = $entityManager;
        $this->profileRepository = $profileRepository;
        $this->userProbabilityRepository = $userProbabilityRepository;
        $this->userProbabilityTrigger = $userProbabilityTrigger;

    }

    /**
     * @DI\Observe(Events::USER_PROBABILITY_UPDATED)
     *
     * @param UserProbabilityUpdatedEvent $userProbabilityUpdatedEvent
     */
    public function onResult(UserProbabilityUpdatedEvent $userProbabilityUpdatedEvent)
    {
        $user = $userProbabilityUpdatedEvent->getUser();
        $agora = $userProbabilityUpdatedEvent->getLearningEntity();

        $profiles = $this->profileRepository->findAll();
        $defaultProbability = 1/count($profiles);

        $parentLinks = $agora->getUplinks();

        foreach ($parentLinks as $parentLink) {
            /* @var Uplink $parentLink */
            $techne = $parentLink->getParent();
            $childrenLinks = $techne->getDownlinks();

            $totalWeight = 0;

            $techneProbabilities = array();
            foreach ($profiles as $profileIndex => $profile) {
                $techneProbabilities[$profileIndex] = 0;
            }

            foreach ($childrenLinks as $childrenLink) {
                /* @var Uplink $childrenLink */
                $child = $childrenLink->getChild();
                $weight = floatval($childrenLink->getWeight());
                $totalWeight+= $weight;

                foreach ($profiles as $profileIndex => $profile) {
                    /* @var UserProbability $userProbability */
                    $userProbability = $this->userProbabilityRepository->findOneBy(array('user'=>$user,'learningEntity'=>$child, 'profile'=>$profile));

                    $probability = $userProbability ? floatval($userProbability->getProbability()) : $defaultProbability;
                    $techneProbabilities[$profileIndex]+= $weight*$probability;
                }
            }

            foreach ($profiles as $profileIndex => $profile) {
                $techneProbabilities[$profileIndex] = $totalWeight>0 ? $techneProbabilities[$profileIndex]/$totalWeight : 0;
            }

            $userProbabilities = array();
            foreach ($profiles as $profileIndex => $profile) {
                $userProbability = $this->userProbabilityRepository->findOneBy(array('user'=>$user,'learningEntity'=>$techne, 'profile'=>$profile));
                if (!$userProbability) {
                    $userProbability = new UserProbability();
                    $userProbability->setUser($user);
                    $userProbability->setLearningEntity($techne);
                    $userProbability->setProfile($profile);
                }
                $userProbability->setProbability($techneProbabilities[$profileIndex]);
                $this->entityManager->persist($userProbability);
                $userProbabilities[] = $userProbability;
            }

            $events = $this->userProbabilityTrigger->getEvents($userProbabilities);
            foreach ($events as $event) {
                $this->entityManager->persist($event);
                $user->addEvent($event);
            }

            $this->entityManager->flush();
        }
    }
}
