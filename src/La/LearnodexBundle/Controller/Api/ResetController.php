<?php

namespace La\LearnodexBundle\Controller\Api;

use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\Repository\TechneRepository;
use La\CoreBundle\Entity\Techne;
use La\CoreBundle\Entity\Event;
use La\CoreBundle\Entity\Outcome;
use La\CoreBundle\Entity\Repository\OutcomeProbabilityRepository;
use La\CoreBundle\Entity\Repository\ProfileRepository;
use La\CoreBundle\Entity\Repository\UserProbabilityRepository;
use La\CoreBundle\Entity\Trace;
use La\CoreBundle\Entity\Uplink;
use La\CoreBundle\Entity\User;
use La\CoreBundle\Entity\UserProbability;
use La\CoreBundle\Event\MissingOutcomeProbabilityEvent;
use La\CoreBundle\Event\MissingUserProbabilityEvent;
use La\CoreBundle\Event\TraceEvent;
use La\CoreBundle\Events;
use La\CoreBundle\Model\Probability\BayesTheorem;
use La\CoreBundle\Model\Probability\OutcomeProbabilityCollection;
use La\CoreBundle\Model\Probability\UserProbabilityCollection;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\SecurityContextInterface;

class ResetController
{
    /**
     * @var SecurityContextInterface
     */
    private $securityContext;
    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * @var ObjectRepository
     */
    private $userRepository;

    /**
     * @var ObjectRepository
     */
    private $outcomeRepository;

    /**
     * @var TechneRepository
     */
    private $techneRepository;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var UserProbabilityRepository
     */
    private $userProbabilityRepository;

    /**
     * @var OutcomeProbabilityRepository
     */
    private $outcomeProbabilityRepository;

    /**
     * @var ProfileRepository
     */
    private $profileRepository;

    /**
     * @var UserProbabilityCollection
     */
    private $userProbabilityCollection;

    /**
     * @var OutcomeProbabilityCollection
     */
    private $outcomeProbabilityCollection;

    /**
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext
     * @param ObjectManager $entityManager
     * @param ObjectRepository $userRepository
     * @param ObjectRepository $outcomeRepository
     * @param TechneRepository $techneRepository
     * @param EventDispatcherInterface $eventDispatcher
     * @param UserProbabilityRepository $userProbabilityRepository
     * @param OutcomeProbabilityRepository $outcomeProbabilityRepository
     * @param ProfileRepository $profileRepository
     * @param UserProbabilityCollection $userProbabilityCollection
     * @param OutcomeProbabilityCollection $outcomeProbabilityCollection
     *
     * @DI\InjectParams({
     *     "securityContext" = @DI\Inject("security.context"),
     *     "entityManager" = @DI\Inject("doctrine.orm.entity_manager"),
     *     "userRepository" = @DI\Inject("la_core.repository.user"),
     *     "outcomeRepository" = @DI\Inject("la_core.repository.outcome"),
     *     "techneRepository" = @DI\Inject("la_core.repository.techne"),
     *     "eventDispatcher" = @DI\Inject("event_dispatcher"),
     *     "userProbabilityRepository" = @DI\Inject("la_core.repository.user_probability"),
     *     "outcomeProbabilityRepository" = @DI\Inject("la_core.repository.outcome_probability"),
     *     "profileRepository" = @DI\Inject("la_core.repository.profile"),
     *     "userProbabilityCollection" = @DI\Inject("la.core_bundle.model.probability.user_probability_collection"),
     *     "outcomeProbabilityCollection" = @DI\Inject("la.core_bundle.model.probability.outcome_probability_collection")
     * })
     */
    public function __construct(
        SecurityContextInterface $securityContext,
        ObjectManager $entityManager,
        ObjectRepository $userRepository,
        ObjectRepository $outcomeRepository,
        TechneRepository $techneRepository,
        EventDispatcherInterface $eventDispatcher,
        UserProbabilityRepository $userProbabilityRepository,
        OutcomeProbabilityRepository $outcomeProbabilityRepository,
        ProfileRepository $profileRepository,
        UserProbabilityCollection $userProbabilityCollection,
        OutcomeProbabilityCollection $outcomeProbabilityCollection
    )
    {
        $this->securityContext = $securityContext;
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->outcomeRepository = $outcomeRepository;
        $this->techneRepository = $techneRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->userProbabilityRepository = $userProbabilityRepository;
        $this->outcomeProbabilityRepository = $outcomeProbabilityRepository;
        $this->profileRepository = $profileRepository;
        $this->userProbabilityCollection = $userProbabilityCollection;
        $this->outcomeProbabilityCollection = $outcomeProbabilityCollection;
    }


    /**
     * @param int $userId
     *
     * @return View
     *
     * @throws NotFoundHttpException if the user cannot be found
     *
     * @Doc\ApiDoc(
     *  section="Learnodex",
     *  description="Recalculates all userProbabilities for the existing traces",
     *  statusCodes={
     *      204="No content returned when successful",
     *      404="Returned when no user or outcome is found",
     *  })
     */
    public function recalculateAction($userId)
    {
        /** @var $user User */
        $user = $this->securityContext->getToken()->getUser();
        if ($userId) {
            $user = $this->entityManager->getRepository('LaCoreBundle:User')->find($userId);
        }

        $up = array();
        $userProbabilities = $this->userProbabilityRepository->getAllUserProbabilities($user);
        /** @var UserProbability $userProbability */
        foreach ($userProbabilities as $userProbability) {
            $userProbability->setProbability(0.2);
            $up[$userProbability->getLearningEntity()->getId()][$userProbability->getProfile()->getId()] = $userProbability;
        }

        $profiles = $this->profileRepository->findAll();
        $defaultProbability = 1/count($profiles);
        $this->outcomeProbabilityCollection->setProfiles($profiles);
        $this->userProbabilityCollection->setProfiles($profiles);

        $traces = $user->getTraces();
        /** @var Trace $trace */
        foreach ($traces as $trace) {
            $outcome = $trace->getOutcome();

            $outcomeProbabilities = $this->outcomeProbabilityRepository->findBy(array('outcome'=>$outcome));
            $this->outcomeProbabilityCollection->setOutcomeProbabilities($outcomeProbabilities);
            if ($this->outcomeProbabilityCollection->hasMissingUserProbabilities())  {
                $this->eventDispatcher->dispatch(Events::MISSING_OUTCOME_PROBABILITY, new MissingOutcomeProbabilityEvent($outcome, $this->outcomeProbabilityCollection));
            }

            $parents = $outcome->getLearningEntity()->getUplinks();
            foreach ($parents as $parent) {
                /** @var Agora $agora */
                $agora = $parent->getParent();

                if (!isset($up[$agora->getId()])) {
                    $this->userProbabilityCollection->setUserProbabilities(array());
                    $this->eventDispatcher->dispatch(Events::MISSING_USER_PROBABILITY, new MissingUserProbabilityEvent($user, $agora, $this->userProbabilityCollection));
                    $up[$agora->getId()] = $this->userProbabilityCollection->getUserProbabilities();
                }
                $userProbabilities = $up[$agora->getId()];

                $denominator = 0;
                foreach ($profiles as $profile) {
                    $userProbability = $userProbabilities[$profile->getId()];
                    $outcomeProbability = $this->outcomeProbabilityCollection->getOutcomeProbabilityForProfile($profile);
                    $denominator+= $userProbability->getProbability() * $outcomeProbability->getProbability();
                }

                foreach ($profiles as $profile) {
                    $userProbability = $userProbabilities[$profile->getId()];
                    $outcomeProbability = $this->outcomeProbabilityCollection->getOutcomeProbabilityForProfile($profile);

                    $newProbabilityValue = $userProbability->getProbability() * $outcomeProbability->getProbability() / $denominator;

                    $userProbability->setProbability($newProbabilityValue);
                }


            }
        }

        $technes = $this->techneRepository->findAll();
        foreach ($technes as $techne) {
            /** @var Techne $techne */
            $userProbabilities = array();
            if (!isset($up[$techne->getId()])) {
                foreach ($profiles as $profile) {
                    $userProbability = new UserProbability();
                    $userProbability->setUser($user);
                    $userProbability->setLearningEntity($techne);
                    $userProbability->setProfile($profile);
                    $userProbability->setProbability(0);
                    $this->entityManager->persist($userProbability);
                    $userProbabilities[] = $userProbability;
                }
            } else {
                $userProbabilities = $up[$techne->getId()];
                foreach ($userProbabilities as $userProbability) {
                    $userProbability->setProbability(0);
                }
            }

            $totalWeight = 0;
            foreach ($techne->getDownlinks() as $childrenLink) {
                /* @var Uplink $childrenLink */
                $childId = $childrenLink->getChild()->getId();
                $weight = floatval($childrenLink->getWeight());
                $totalWeight+= $weight;

                foreach ($userProbabilities as $userProbability) {
                    $probability = isset($up[$childId]) ? $up[$childId][$userProbability->getProfile()->getId()]->getProbability() : $defaultProbability;
                    $userProbability->setProbability($userProbability->getProbability() + $weight*$probability);
                }
            }
            if ($totalWeight>0) {
                foreach ($userProbabilities as $userProbability) {
                    $userProbability->setProbability($userProbability->getProbability()/$totalWeight);
                }

            }

        }

        $this->entityManager->flush();

        return View::create(null, 204);
        //return View::create(['data'=>$up], 200);
    }

}
