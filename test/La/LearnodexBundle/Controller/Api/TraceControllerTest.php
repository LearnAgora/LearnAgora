<?php

namespace test\La\LearnodexBundle\Controller\Api;

use La\CoreBundle\Events;
use La\LearnodexBundle\Controller\Api\TraceController;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTestCase;

class TraceControllerTest extends ProphecyTestCase
{
    private $entityManager;
    private $userRepository;
    private $outcomeRepository;
    private $eventDispatcher;

    /**
     * @var TraceController
     */
    private $sut;

    protected function setUp()
    {
        parent::setUp();

        $this->entityManager = $this->prophesize('\Doctrine\Common\Persistence\ObjectManager');
        $this->userRepository = $this->prophesize('\Doctrine\Common\Persistence\ObjectRepository');
        $this->outcomeRepository = $this->prophesize('\Doctrine\Common\Persistence\ObjectRepository');
        $this->eventDispatcher = $this->prophesize('\Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->sut = new TraceController($this->entityManager->reveal(), $this->userRepository->reveal(), $this->outcomeRepository->reveal(), $this->eventDispatcher->reveal());
    }

    /** @test */
    public function it_stores_a_correct_trace()
    {
        $user = $this->prophesize('\La\CoreBundle\Entity\User');
        $outcome = $this->prophesize('\La\CoreBundle\Entity\Outcome');

        $this->userRepository->find(1)->shouldBeCalled()->willReturn($user->reveal());
        $this->outcomeRepository->find(1)->shouldBeCalled()->willReturn($outcome->reveal());
        $this->entityManager->persist(Argument::type('\La\CoreBundle\Entity\Trace'))->shouldBeCalled();
        $this->entityManager->flush()->shouldBeCalled();

        $this->eventDispatcher->dispatch(Events::TRACE_CREATE, Argument::type('\La\CoreBundle\Event\TraceEvent'))->shouldBeCalled();

        $this->sut->traceAction(1, 1);
    }

    /**
     * @test
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @expectedExceptionMessage User could not be found.
     */
    public function it_throws_exception_if_user_is_not_found()
    {
        $outcome = $this->prophesize('\La\CoreBundle\Entity\Outcome');

        $this->userRepository->find(1)->shouldBeCalled()->willReturn(null);
        $this->outcomeRepository->find(Argument::any())->willReturn($outcome->reveal());
        $this->entityManager->persist(Argument::any())->shouldNotBeCalled();
        $this->entityManager->flush()->shouldNotBeCalled();

        $this->sut->traceAction(1, 1);
    }

    /**
     * @test
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @expectedExceptionMessage Outcome could not be found.
     */
    public function it_throws_exception_if_outcome_is_not_found()
    {
        $user = $this->prophesize('\La\CoreBundle\Entity\User');

        $this->userRepository->find(Argument::any())->willReturn($user->reveal());
        $this->outcomeRepository->find(1)->shouldBeCalled()->willReturn(null);
        $this->entityManager->persist(Argument::any())->shouldNotBeCalled();
        $this->entityManager->flush()->shouldNotBeCalled();

        $this->sut->traceAction(1, 1);
    }
}
