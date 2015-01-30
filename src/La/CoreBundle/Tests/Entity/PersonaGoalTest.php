<?php

namespace La\CoreBundle\Tests\Entity;

use La\CoreBundle\Entity\Persona;
use La\CoreBundle\Entity\PersonaGoal;
use Prophecy\PhpUnit\ProphecyTestCase;

class PersonaGoalTest extends ProphecyTestCase
{

    /**
     * @var Persona
     */
    private $persona;

    /**
     * @var PersonaGoal
     */
    private $sut;

    protected function setUp()
    {
        parent::setUp();

        $this->persona = $this->prophesize('\La\CoreBundle\Entity\Persona');

        $this->sut = new PersonaGoal();
    }

    /** @test */
    public function it_has_a_name()
    {
        $this->persona->getUsername()->willReturn('name');

        $this->sut->setPersona($this->persona->reveal());

        $this->assertEquals('name', $this->sut->getName());
    }
}
