<?php

namespace La\LearnodexBundle\Model;

use Doctrine\Common\Persistence\ObjectRepository;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Model\Action\ActionProvider;
use La\LearnodexBundle\Model\Exception\CardNotFoundException;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Security\Core\SecurityContextInterface;


/**
 * @DI\Service("la_learnodex.goal_based_random_card_provider")
 */
class GoalBasedRandomCardProvider implements RandomCardProviderInterface
{
    /**
     * @var ActionProvider
     */
    private $actionProvider;

    /**
     * @var SecurityContextInterface
     *
     */
    private $securityContext;

    /**
     * @var ObjectRepository
     *
     */
    private $goalRepository;

    /**
     * Constructor.
     *
     * @param ActionProvider $actionProvider
     * @param SecurityContextInterface $securityContext
     * @param ObjectRepository $goalRepository
     *
     * @DI\InjectParams({
     *  "actionProvider" = @DI\Inject("la_core.action_provider"),
     *  "securityContext" = @DI\Inject("security.context"),
     *  "goalRepository" = @DI\Inject("la_core.repository.goal")
     * })
     */
    public function __construct(ActionProvider $actionProvider,SecurityContextInterface $securityContext, ObjectRepository $goalRepository)
    {
        $this->actionProvider = $actionProvider;
        $this->securityContext = $securityContext;
        $this->goalRepository = $goalRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getCard()
    {
        $user = $this->securityContext->getToken()->getUser();
        if (!$user) {
            throw new AuthenticationCredentialsNotFoundException();
        }

        // get the active goal of the authenticated user (can be null)
        $goal = $this->goalRepository->findOneBy(array('user' => $user->getId(), 'active' => true));

        // get a random learning entity belonging to the goal
        $selectedLearningEntity = ($goal)
            ? $this->actionProvider->getRandomActionForGoal($goal)
            : $this->actionProvider->getRandomAction();

        if (!is_null($selectedLearningEntity)) {
            return new Card($selectedLearningEntity);
        }

        throw new CardNotFoundException();

    }
}
