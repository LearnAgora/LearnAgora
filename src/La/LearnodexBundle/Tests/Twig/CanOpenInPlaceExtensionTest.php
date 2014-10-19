<?php

use Prophecy\PhpUnit\ProphecyTestCase;
use La\LearnodexBundle\Twig\CanOpenInPlaceExtension;

class CanOpenInPlaceExtensionTest extends ProphecyTestCase {
    /** @var CanOpenInPlaceExtension */
    private $xCOIP;

    protected function setUp() {
        parent::setUp();

        $this->xCOIP = new CanOpenInPlaceExtension();
    }

    /** @test */
    public function it_returns_false_on_google() {
        $this->assertFalse($this->xCOIP->canOpenInPlaceFilter("http://www.google.com"));
    }

    /** @test */
    public function it_returns_true_on_bing() {
        $this->assertTrue($this->xCOIP->canOpenInPlaceFilter("http://www.bing.com"));
    }
} 