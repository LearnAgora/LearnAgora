<?php

namespace La\AngularBundle\Tests\Controller;

use La\AngularBundle\Controller\DefaultController;
use Prophecy\PhpUnit\ProphecyTestCase;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class DefaultControllerTest extends ProphecyTestCase
{
    /**
     * @var EngineInterface
     */
    private $twig;

    /**
     * @var DefaultController
     */
    private $sut;

    protected function setUp()
    {
        parent::setUp();

        $this->twig = $this->prophesize('\Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
        $this->sut = new DefaultController($this->twig->reveal());
    }

    /** @test */
    public function it_only_loads_the_initial_ui()
    {
        $this->twig->renderResponse('LaAngularBundle:Default:card.html.twig')->shouldBeCalled()->willReturn('initial ui');

        $this->assertEquals('initial ui', $this->sut->cardAction());
    }
}
