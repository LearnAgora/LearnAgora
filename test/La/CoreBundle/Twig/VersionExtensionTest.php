<?php

namespace test\La\CoreBundle\Tests\Twig;

use La\CoreBundle\Model\Version\VersionProviderInterface;
use La\CoreBundle\Twig\VersionExtension;
use Prophecy\PhpUnit\ProphecyTestCase;

class VersionExtensionTest extends ProphecyTestCase
{
    /**
     * @var VersionProviderInterface
     */
    private $provider;

    /**
     * @var VersionExtension
     */
    private $sut;

    protected function setUp()
    {
        parent::setUp();

        $this->provider = $this->prophesize('\La\CoreBundle\Model\Version\VersionProviderInterface');
        $this->sut = new VersionExtension($this->provider->reveal());
    }

    /** @test */
    public function it_has_a_name()
    {
        $this->assertNotNull($this->sut->getName());
    }

    /** @test */
    public function it_has_functions()
    {
        $this->assertNotNull($this->sut->getFunctions());
    }

    /** @test */
    public function it_gets_the_version_from_the_provider()
    {
        $this->provider->getVersion()->shouldBeCalled()->willReturn('version');

        $this->assertSame('version', $this->sut->versionAsString());
    }
}
