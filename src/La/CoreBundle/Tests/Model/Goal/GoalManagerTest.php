<?php

namespace La\CoreBundle\Tests\Model\Goal;

use Doctrine\Common\Persistence\ObjectRepository;
use La\CoreBundle\Model\Goal\GoalManager;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class GoalManagerTest extends ProphecyTestCase
{
    const GOAL_ID = 1;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * @var TokenInterface
     */
    private $token;

    /**
     * @var UserInterface
     */
    private $user;

    /**
     * @var ObjectRepository
     */
    private $goalRepository;

    /**
     * @var ObjectRepository
     */
    private $affinityRepository;

    /**
     * @var ObjectRepository
     */
    private $personaMatchRepository;

    /**
     * @var GoalManager
     */
    private $sut;

    protected function setUp()
    {
        parent::setUp();

        $this->session = $this->prophesize('\Symfony\Component\HttpFoundation\Session\SessionInterface');
        $this->securityContext = $this->prophesize('\Symfony\Component\Security\Core\SecurityContextInterface');
        $this->token = $this->prophesize('\Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $this->user = $this->prophesize('\Symfony\Component\Security\Core\User\UserInterface');
        $this->goalRepository = $this->prophesize('\Doctrine\Common\Persistence\ObjectRepository');
        $this->affinityRepository = $this->prophesize('\Doctrine\Common\Persistence\ObjectRepository');
        $this->personaMatchRepository = $this->prophesize('\Doctrine\Common\Persistence\ObjectRepository');

        $this->token->getUser()->willReturn($this->user->reveal());
        $this->securityContext->getToken()->willReturn($this->token->reveal());

        $this->sut = new GoalManager($this->session->reveal(), $this->securityContext->reveal(), $this->goalRepository->reveal(), $this->affinityRepository->reveal(), $this->personaMatchRepository->reveal());
    }

    /** @test */
    // TODO: turns out to not be necessary, remove
    public function it_updates_the_stored_persona_goal_if_there_is_an_active_goal()
    {
        $this->session->get('goalId', false)->shouldBeCalled()->willReturn(self::GOAL_ID);
        $this->session->set('goalName', 'goal name')->shouldBeCalled();

        $persona = $this->prophesize('\La\CoreBundle\Entity\Persona');
        $goal = $this->prophesize('\La\CoreBundle\Entity\PersonaGoal');
        $goal->getName()->willReturn('goal name');
        $goal->getPersona()->willReturn($persona->reveal());

        $this->goalRepository->find(self::GOAL_ID)->shouldBeCalled()->willReturn($goal->reveal());

        $this->personaMatchRepository->findOneBy(array('user' => $this->user->reveal(), 'persona' => $persona->reveal()))->shouldBeCalled();

        $this->session->set('goalAffinity', 0)->shouldBeCalled();

        $this->sut->updateGoal();
    }

    /** @test */
    // TODO: turns out to not be necessary, remove
    public function it_updates_the_stored_agora_goal_if_there_is_an_active_goal()
    {
        $this->session->get('goalId', false)->shouldBeCalled()->willReturn(self::GOAL_ID);
        $this->session->set('goalName', 'goal name')->shouldBeCalled();

        $agora = $this->prophesize('\La\CoreBundle\Entity\Agora');
        $goal = $this->prophesize('\La\CoreBundle\Entity\AgoraGoal');
        $goal->getName()->willReturn('goal name');
        $goal->getAgora()->willReturn($agora->reveal());

        $this->goalRepository->find(self::GOAL_ID)->shouldBeCalled()->willReturn($goal->reveal());

        $affinity = $this->prophesize('\La\CoreBundle\Entity\Affinity');
        $affinity->getValue()->willReturn(0);

        $this->affinityRepository->findOneBy(array('user' => $this->user->reveal(), 'agora' => $agora->reveal()))->shouldBeCalled()->willReturn($affinity->reveal());

        $this->session->set('goalAffinity', 0)->shouldBeCalled();

        $this->sut->updateGoal();
    }

    /** @test */
    // TODO: turns out to not be necessary, remove
    public function it_does_not_update_anything_if_there_is_no_active_goal()
    {
        $this->session->get('goalId', false)->shouldBeCalled()->willReturn(false);
        $this->goalRepository->find(Argument::any())->shouldNotBeCalled();

        $this->sut->updateGoal();
    }
}
