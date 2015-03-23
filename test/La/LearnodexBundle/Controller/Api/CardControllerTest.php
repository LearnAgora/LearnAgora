<?php

namespace test\La\LearnodexBundle\Controller\Api;

use La\LearnodexBundle\Controller\Api\CardController;
use La\LearnodexBundle\Model\Exception\CardNotFoundException;
use La\LearnodexBundle\Model\RandomCardProviderInterface;
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
        $this->randomCardProvider->getCard()->shouldBeCalled()->willReturn($randomCard);

        $view = $this->sut->randomCardAction();

        $this->assertInstanceOf('\FOS\RestBundle\View\View', $view);
        $this->assertEquals(200, $view->getStatusCode());
        $this->assertSame($randomCard, $view->getData());
    }

    /**
     * @test
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @expectedExceptionMessage No random card could be found.
     */
    public function it_throws_exception_if_no_random_card_is_found()
    {
        $this->randomCardProvider->getCard()->shouldBeCalled()->willThrow(new CardNotFoundException());

        $this->sut->randomCardAction();
    }
}
