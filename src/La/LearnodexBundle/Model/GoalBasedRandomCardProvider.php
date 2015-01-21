<?php

namespace La\LearnodexBundle\Model;

use Doctrine\Common\Persistence\ObjectRepository;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Model\Action\ActionProvider;
use La\LearnodexBundle\Model\Exception\CardNotFoundException;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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
     * @var SessionInterface
     *
     */
    private $session;

    /**
     * @var ObjectRepository
     *
     */
    private $goalRepository;

    /**
     * Constructor.
     *
     * @param ActionProvider $actionProvider
     * @param SessionInterface $session
     * @param ObjectRepository $goalRepository
     *
     * @DI\InjectParams({
     *  "actionProvider" = @DI\Inject("la_core.action_provider"),
     *  "session" = @DI\Inject("session"),
     *  "goalRepository" = @DI\Inject("la_core.repository.goal")
     * })
     */
    public function __construct(ActionProvider $actionProvider,SessionInterface $session, ObjectRepository $goalRepository)
    {
        $this->actionProvider = $actionProvider;
        $this->session = $session;
        $this->goalRepository = $goalRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getCard()
    {
        /* Goal $goal */
        $goal = $this->session->has('goalId') ? $this->goalRepository->find($this->session->get('goalId')) : null;

        /* LearningEntity $selectedAction */
        $selectedLearningEntity = null;

        if ($goal) {
            $selectedLearningEntity = $this->actionProvider->getRandomActionForGoal($goal);
        } else {
            $selectedLearningEntity = $this->actionProvider->getRandomAction();
        }

        if (!is_null($selectedLearningEntity)) {
            return new Card($selectedLearningEntity);
        }

        throw new CardNotFoundException();

    }
}
