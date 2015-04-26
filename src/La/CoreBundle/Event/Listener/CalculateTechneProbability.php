<?php

namespace La\CoreBundle\Event\Listener;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Repository\ProfileRepository;
use La\CoreBundle\Entity\Repository\UserProbabilityRepository;
use La\CoreBundle\Entity\Uplink;
use La\CoreBundle\Entity\UserProbability;
use La\CoreBundle\Event\AffinityUpdatedEvent;
use La\CoreBundle\Events;


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

     *
     * @DI\InjectParams({
     *  "entityManager" = @DI\Inject("doctrine.orm.entity_manager"),
     *  "profileRepository" = @DI\Inject("la_core.repository.profile"),
     *  "userProbabilityRepository" = @DI\Inject("la_core.repository.user_probability")
     * })
     */
    public function __construct(ObjectManager $entityManager, ProfileRepository $profileRepository, UserProbabilityRepository $userProbabilityRepository)
    {
        $this->entityManager = $entityManager;
        $this->profileRepository = $profileRepository;
        $this->userProbabilityRepository = $userProbabilityRepository;

    }

    /**
     * @DI\Observe(Events::AFFINITY_UPDATED)
     *
     * @param AffinityUpdatedEvent $affinityUpdatedEvent
     */
    public function onResult(AffinityUpdatedEvent $affinityUpdatedEvent)
    {
        $affinity = $affinityUpdatedEvent->getAffinity();
        $user = $affinity->getUser();
        $agora = $affinity->getAgora();

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

            foreach ($profiles as $profileIndex => $profile) {
                $userTechneProbability = $this->userProbabilityRepository->findOneBy(array('user'=>$user,'learningEntity'=>$techne, 'profile'=>$profile));
                if (!$userTechneProbability) {
                    $userTechneProbability = new UserProbability();
                    $userTechneProbability->setUser($user);
                    $userTechneProbability->setLearningEntity($techne);
                    $userTechneProbability->setProfile($profile);
                }
                $userTechneProbability->setProbability($techneProbabilities[$profileIndex]);
                $this->entityManager->persist($userTechneProbability);
            }
            $this->entityManager->flush();
        }
    }
}
