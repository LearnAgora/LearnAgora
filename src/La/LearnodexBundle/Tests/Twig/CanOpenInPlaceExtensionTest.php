<?php

namespace La\LearnodexBundle\Tests\Model;

use GuzzleHttp\Client;
use Prophecy\PhpUnit\ProphecyTestCase;
use La\LearnodexBundle\Twig\CanOpenInPlaceExtension;

class CanOpenInPlaceExtensionTest extends ProphecyTestCase
{
    const URL = 'http://localhost';
    /**
     * @var Client
     */
    private $client;

    /**
     * @var CanOpenInPlaceExtension
     */
    private $sut;

    protected function setUp()
    {
        parent::setUp();

        $this->client = $this->prophesize('\GuzzleHttp\Client');
        $this->sut = new CanOpenInPlaceExtension($this->client->reveal());
    }

    /** @test */
    public function it_has_a_name()
    {
        $this->assertNotNull($this->sut->getName());
    }

    /** @test */
    public function it_has_filters()
    {
        $this->assertNotNull($this->sut->getFilters());
    }

    /** @test */
    public function it_returns_false_on_sites_that_have_deny_frame_options()
    {
        $response = $this->prophesize('\GuzzleHttp\Message\MessageInterface');
        $response->getHeader('x-frame-options')->willReturn('deny');

        $this->client->head(self::URL)->willReturn($response->reveal());

        $this->assertFalse($this->sut->canOpenInPlaceFilter(self::URL));
    }

    /** @test */
    public function it_returns_false_on_sites_that_have_same_origin_frame_options()
    {
        $response = $this->prophesize('\GuzzleHttp\Message\MessageInterface');
        $response->getHeader('x-frame-options')->willReturn('sameorigin');

        $this->client->head(self::URL)->willReturn($response->reveal());

        $this->assertFalse($this->sut->canOpenInPlaceFilter(self::URL));
    }

    /** @test */
    public function it_returns_false_on_sites_that_have_allow_from_frame_options()
    {
        $response = $this->prophesize('\GuzzleHttp\Message\MessageInterface');
        $response->getHeader('x-frame-options')->willReturn('allow-from');

        $this->client->head(self::URL)->willReturn($response->reveal());

        $this->assertFalse($this->sut->canOpenInPlaceFilter(self::URL));
    }

    /** @test */
    public function it_returns_true_on_sites_with_open_frame_options()
    {
        $response = $this->prophesize('\GuzzleHttp\Message\MessageInterface');
        $response->getHeader('x-frame-options')->willReturn('');

        $this->client->head(self::URL)->willReturn($response->reveal());

        $this->assertTrue($this->sut->canOpenInPlaceFilter(self::URL));
    }

    /** @test */
    public function it_checks_things_in_case_insensitive_ways()
    {
        $response = $this->prophesize('\GuzzleHttp\Message\MessageInterface');
        $response->getHeader('x-frame-options')->willReturn('SAMEORIGIN');

        $this->client->head(self::URL)->willReturn($response->reveal());

        $this->assertFalse($this->sut->canOpenInPlaceFilter(self::URL));
    }
}
