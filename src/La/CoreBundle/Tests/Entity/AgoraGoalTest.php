<?php

namespace La\CoreBundle\Tests\Entity;

use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\AgoraGoal;
use Prophecy\PhpUnit\ProphecyTestCase;

class AgoraGoalTest extends ProphecyTestCase
{
    /**
     * @var Agora
     */
    private $agora;

    /**
     * @var AgoraGoal
     */
    private $sut;

    protected function setUp()
    {
        parent::setUp();

        $this->agora = $this->prophesize('\La\CoreBundle\Entity\Agora');

        $this->sut = new AgoraGoal();
    }


    /** @test */
    public function it_has_a_name()
    {
        $this->agora->getName()->willReturn('name');

        $this->sut->setAgora($this->agora->reveal());

        $this->assertEquals('name', $this->sut->getName());
    }
}
