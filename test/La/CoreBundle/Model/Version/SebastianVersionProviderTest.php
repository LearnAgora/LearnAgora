<?php

namespace test\La\CoreBundle\Model\Version;

use La\CoreBundle\Model\Version\SebastianVersionProvider;
use Prophecy\PhpUnit\ProphecyTestCase;
use SebastianBergmann\Version;

class SebastianVersionProviderTest extends ProphecyTestCase
{
    /**
     * @var Version
     */
    private $sebastian;

    /**
     * @var SebastianVersionProvider
     */
    private $sut;

    protected function setUp()
    {
        parent::setUp();

        $this->sebastian = $this->prophesize('\SebastianBergmann\Version');
        $this->sut = new SebastianVersionProvider($this->sebastian->reveal());
    }

    /** @test */
    public function it_gets_the_version_from_sebastian()
    {
        $this->sebastian->getVersion()->shouldBeCalled()->willReturn('version');

        $this->assertSame('version', $this->sut->getVersion());
    }
}
