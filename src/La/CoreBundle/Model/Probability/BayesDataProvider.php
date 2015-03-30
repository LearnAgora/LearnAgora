<?php

namespace La\CoreBundle\Model\Probability;

use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\Outcome;
use La\CoreBundle\Entity\Repository\UserProbabilityRepository;
use La\CoreBundle\Entity\User;
use La\CoreBundle\Event\MissingOutcomeProbabilityEvent;
use La\CoreBundle\Event\MissingUserProbabilityEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use La\CoreBundle\Events;

class BayesDataProvider
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var UserProbabilityRepository
     */
    private $userProbabilityRepository;

    /**
     * Constructor.
     *
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @DI\InjectParams({
     *  "eventDispatcher" = @DI\Inject("event_dispatcher")
     * })
     */
    public function __construct(EventDispatcherInterface $eventDispatcher) {
        $this->eventDispatcher = $eventDispatcher;
        $this->bayes = new Bayes();
    }

    /**
     * @param User $user
     * @param Agora $agora
     * @param Outcome $outcome
     * @return BayesData
     */
    public function load(User $user, Agora $agora, Outcome $outcome) {
        $results = $this->userProbabilityRepository->loadQueryForProbabilitiesFor($user, $agora, $outcome)->getResult();

        $bayesData = new BayesData($results);


        return $bayesData;
    }

    public function validate(BayesData $bayesData) {
        if ($bayesData->hasNullUserProbability()) {
            $this->eventDispatcher->dispatch(Events::MISSING_USER_PROBABILITY, new MissingUserProbabilityEvent($user, $agora, $bayesData));
        }

        if ($bayesData->hasNullOutcomeProbability()) {
            $this->eventDispatcher->dispatch(Events::MISSING_OUTCOME_PROBABILITY, new MissingOutcomeProbabilityEvent($outcome, $bayesData));
        }

    }


}
