<?php

namespace La\CoreBundle\Tests\Entity;

use La\CoreBundle\Entity\Persona;
use La\CoreBundle\Entity\User;
use Prophecy\PhpUnit\ProphecyTestCase;

class PersonaTest extends ProphecyTestCase
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var Persona
     */
    private $sut;

    protected function setUp()
    {
        parent::setUp();

        $this->user = $this->prophesize('\La\CoreBundle\Entity\User');

        $this->sut = new Persona();
    }

    /** @test */
    public function it_gets_the_username_from_the_user()
    {
        $this->user->getUsername()->willReturn('name');

        $this->sut->setUser($this->user->reveal());

        $this->assertEquals('name', $this->sut->getUsername());
    }

    /** @test */
    public function it_returns_null_when_user_is_unknown()
    {
        $this->assertNull($this->sut->getUsername());
    }
}
