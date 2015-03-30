<?php

namespace spec\La\CoreBundle\Model\Probability;

use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Model\Probability\BayesData;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use La\CoreBundle\Entity\Repository\UserProbabilityRepository;
use La\CoreBundle\Entity\User;
use La\CoreBundle\Entity\Outcome;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class UserProbabilitiesSpec extends ObjectBehavior
{
    function let(UserProbabilityRepository $userProbabilityRepository, ObjectManager $entityManager, EventDispatcherInterface $eventDispatcher)
    {
        $this->beConstructedWith($userProbabilityRepository,$entityManager,$eventDispatcher);

        $this->shouldHaveType('La\CoreBundle\Model\Probability\UserProbabilities');
    }

    function it_raises_an_exception_when_processing_an_outcome_without_agora_being_set(Outcome $outcome, User $user)
    {
        $this->setUser($user);

        $this->shouldThrow('La\CoreBundle\Model\Exception\ObjectErrorException')->duringProcessOutcome($outcome);
    }

    function it_raises_an_exception_when_processing_an_outcome_without_user_being_set(Outcome $outcome, Agora $agora)
    {
        $this->setLearningEntity($agora);

        $this->shouldThrow('La\CoreBundle\Model\Exception\ObjectErrorException')->duringProcessOutcome($outcome);
    }
/*
    function it_raises_no_exception_when_user_and_agora_are_set(UserProbabilityRepository $userProbabilityRepository, BayesData $bayesData, Outcome $outcome, User $user, Agora $agora) {
        $this->setUser($user);
        $this->setLearningEntity($agora);

        $userProbabilityRepository->loadProbabilitiesFor($user,$agora,$outcome)->willReturn($bayesData);

        $this->shouldNotThrow('La\CoreBundle\Model\Exception\ObjectErrorException')->duringProcessOutcome($outcome);
    }
*/
}
