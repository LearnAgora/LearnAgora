<?php

namespace La\LearnodexBundle\Tests\Model;

use La\LearnodexBundle\Model\Exception\CardNotFoundException;
use La\LearnodexBundle\Model\RandomCardProviderInterface;
use La\SandboxBundle\Controller\CardController;
use Prophecy\PhpUnit\ProphecyTestCase;
use stdClass;

class CardControllerTest extends ProphecyTestCase
{
    /**
     * @var RandomCardProviderInterface
     */
    private $randomCardProvider;

    /**
     * @var CardController
     */
    private $sut;

    protected function setUp()
    {
        parent::setUp();

        $this->randomCardProvider = $this->prophesize('\La\LearnodexBundle\Model\RandomCardProviderInterface');
        $this->sut = new CardController($this->randomCardProvider->reveal());
    }

    /** @test */
    public function it_returns_a_random_card()
    {
        $randomCard = new stdClass();
        $this->randomCardProvider->get()->shouldBeCalled()->willReturn($randomCard);

        $this->assertSame($randomCard, $this->sut->randomAction());
    }

    /**
     * @test
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function it_throws_exception_if_no_random_card_is_found()
    {
        $exception = new CardNotFoundException();
        $this->randomCardProvider->get()->shouldBeCalled()->willThrow($exception);

        $this->sut->randomAction();
    }
}
