<?php

namespace La\LearnodexBundle\Controller\Api;

use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Event;
use La\CoreBundle\Entity\Outcome;
use La\CoreBundle\Entity\Repository\OutcomeProbabilityRepository;
use La\CoreBundle\Entity\Repository\ProfileRepository;
use La\CoreBundle\Entity\Repository\UserProbabilityRepository;
use La\CoreBundle\Entity\Trace;
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
     * @var BayesTheorem
     */
    private $bayesTheorem;

    /**
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext
     * @param ObjectManager $entityManager
     * @param ObjectRepository $userRepository
     * @param ObjectRepository $outcomeRepository
     * @param EventDispatcherInterface $eventDispatcher
     * @param UserProbabilityRepository $userProbabilityRepository
     * @param OutcomeProbabilityRepository $outcomeProbabilityRepository
     * @param ProfileRepository $profileRepository
     * @param UserProbabilityCollection $userProbabilityCollection
     * @param OutcomeProbabilityCollection $outcomeProbabilityCollection
     * @param BayesTheorem $bayesTheorem
     *
     * @DI\InjectParams({
     *     "securityContext" = @DI\Inject("security.context"),
     *     "entityManager" = @DI\Inject("doctrine.orm.entity_manager"),
     *     "userRepository" = @DI\Inject("la_core.repository.user"),
     *     "outcomeRepository" = @DI\Inject("la_core.repository.outcome"),
     *     "eventDispatcher" = @DI\Inject("event_dispatcher"),
     *     "userProbabilityRepository" = @DI\Inject("la_core.repository.user_probability"),
     *     "outcomeProbabilityRepository" = @DI\Inject("la_core.repository.outcome_probability"),
     *     "profileRepository" = @DI\Inject("la_core.repository.profile"),
     *     "userProbabilityCollection" = @DI\Inject("la.core_bundle.model.probability.user_probability_collection"),
     *     "outcomeProbabilityCollection" = @DI\Inject("la.core_bundle.model.probability.outcome_probability_collection"),
     *     "bayesTheorem" = @DI\Inject("la.core_bundle.model.probability.bayes_theorem")
     * })
     */
    public function __construct(
        SecurityContextInterface $securityContext,
        ObjectManager $entityManager,
        ObjectRepository $userRepository,
        ObjectRepository $outcomeRepository,
        EventDispatcherInterface $eventDispatcher,
        UserProbabilityRepository $userProbabilityRepository,
        OutcomeProbabilityRepository $outcomeProbabilityRepository,
        ProfileRepository $profileRepository,
        UserProbabilityCollection $userProbabilityCollection,
        OutcomeProbabilityCollection $outcomeProbabilityCollection,
        BayesTheorem $bayesTheorem
    )
    {
        $this->securityContext = $securityContext;
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->outcomeRepository = $outcomeRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->userProbabilityRepository = $userProbabilityRepository;
        $this->outcomeProbabilityRepository = $outcomeProbabilityRepository;
        $this->profileRepository = $profileRepository;
        $this->userProbabilityCollection = $userProbabilityCollection;
        $this->outcomeProbabilityCollection = $outcomeProbabilityCollection;
        $this->bayesTheorem = $bayesTheorem;
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

        //clear the current $userProbabilities
        $userProbabilities = $this->userProbabilityRepository->getAllUserProbabilities($user);
        /** @var UserProbability $userProbability */
        foreach ($userProbabilities as $userProbability) {
            $this->entityManager->remove($userProbability);
        }
        $this->entityManager->flush();

        $profiles = $this->profileRepository->findAll();
        $this->outcomeProbabilityCollection->setProfiles($profiles);
        $this->userProbabilityCollection->setProfiles($profiles);

        $traces = $user->getTraces();
        /** @var Trace $trace */
        foreach ($traces as $trace) {
            $outcome = $trace->getOutcome();
            $learningEntity = $outcome->getLearningEntity();
            $parents = $learningEntity->getUplinks();

            $outcomeProbabilities = $this->outcomeProbabilityRepository->findBy(array('outcome'=>$outcome));
            $this->outcomeProbabilityCollection->setOutcomeProbabilities($outcomeProbabilities);
            if ($this->outcomeProbabilityCollection->hasMissingUserProbabilities())  {
                $this->eventDispatcher->dispatch(Events::MISSING_OUTCOME_PROBABILITY, new MissingOutcomeProbabilityEvent($outcome, $this->outcomeProbabilityCollection));
            }


            foreach ($parents as $parent) {
                $agora = $parent->getParent();

                $userProbabilities = $this->userProbabilityRepository->findBy(array('user'=>$user, 'learningEntity' => $agora));
                $this->userProbabilityCollection->setUserProbabilities($userProbabilities);

                if ($this->userProbabilityCollection->hasMissingUserProbabilities()) {
                    $this->eventDispatcher->dispatch(Events::MISSING_USER_PROBABILITY, new MissingUserProbabilityEvent($user, $agora, $this->userProbabilityCollection));
                }

                $this->bayesTheorem->applyTo($this->userProbabilityCollection, $this->outcomeProbabilityCollection);


                $this->entityManager->flush();

                //$this->eventDispatcher->dispatch(Events::USER_PROBABILITY_UPDATED, new UserProbabilityUpdatedEvent($user,$agora));
            }

            $this->entityManager->flush();

        }
        //return View::create(null, 204);
        return View::create(['data'=>$userProbabilities], 200);
    }

}
