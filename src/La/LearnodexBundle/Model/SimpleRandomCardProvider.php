<?php

namespace La\LearnodexBundle\Model;

use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Model\Action\ActionProvider;
use La\LearnodexBundle\Model\Exception\CardNotFoundException;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @DI\Service("la_learnodex.simple_random_card_provider")
 */
class SimpleRandomCardProvider implements RandomCardProviderInterface
{
    /**
     * @var ActionProvider
     */
    private $actionProvider;

    /**
     * Constructor.
     *
     * @param ActionProvider $actionProvider
     *
     * @DI\InjectParams({
     *  "actionProvider" = @DI\Inject("la_core.action_provider")
     * })
     */
    public function __construct(ActionProvider $actionProvider)
    {
        $this->actionProvider = $actionProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function getCard()
    {
        $session = new Session();
        $goal = $session->has('goal') ? $goal = $session->get('goal') : null;

        $selectedLearningEntity = $this->actionProvider->findOneOrNullUnvisitedActions($goal);

        if (is_null($selectedLearningEntity)) {
            $selectedLearningEntity = $this->actionProvider->findOneOrNullPostponedActions();
        }

        if (!is_null($selectedLearningEntity)) {
            return new Card($selectedLearningEntity);
        }

        throw new CardNotFoundException();

    }
}
