<?php

namespace spec\La\CoreBundle\Model\Probability;

use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\Outcome;
use La\CoreBundle\Entity\User;
use La\CoreBundle\Entity\UserProbability;
use La\CoreBundle\Event\MissingUserProbabilityEvent;
use La\CoreBundle\Events;
use La\CoreBundle\Model\Probability\BayesData;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use La\CoreBundle\Entity\Repository\UserProbabilityRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class BayesDataProviderSpec extends ObjectBehavior
{
    function let(EventDispatcherInterface $eventDispatcher)
    {
        $this->beConstructedWith($eventDispatcher);
        $this->shouldHaveType('La\CoreBundle\Model\Probability\BayesDataProvider');
    }
/*
    function it_can_provide_bayes_data(User $user, Agora $agora)
    {
        $this->load($user,$agora)->shouldHaveType('La\CoreBundle\Model\Probability\BayesData');
    }

    function it_uses_the_user_probability_repository(UserProbabilityRepository $userProbabilityRepository, BayesData $bayesData, Outcome $outcome, User $user, Agora $agora)
    {
        $userProbabilityRepository->loadProbabilitiesFor($user,$agora,$outcome)->willReturn($bayesData);
    }

    function it_checks_for_missing_userProbabilities(EventDispatcherInterface $eventDispatcher, User $user, Agora $agora)
    {
        $bayesData = new BayesData(array(
            BayesData::PROFILE_ID =>1,
            BayesData::USER_PROBABILITY => null,
            BayesData::OUTCOME_PROBABILITY_VALUE => null
        ));

        $eventDispatcher->dispatch(Events::MISSING_USER_PROBABILITY, Argument::type('La\CoreBundle\Event\MissingUserProbabilityEvent'))->shouldBeCalled();

        $this->dosomething($bayesData);
    }

    function it_return_bayes_data_if_no_nulls(BayesData $bayesData,EventDispatcherInterface $eventDispatcher, User $user, Agora $agora)
    {
        $bayesData->add(array(
            "profileId"=>1,
            "userProbability" => new UserProbability(),
            "outcomeProbability" => 0
        ));

        $eventDispatcher->dispatch(Events::MISSING_USER_PROBABILITY, Argument::type('La\CoreBundle\Event\MissingUserProbabilityEvent'))->shouldNotBeCalled();

        $this->load($user,$agora)->shouldHaveType('La\CoreBundle\Model\Probability\BayesData');
    }

*/
}
