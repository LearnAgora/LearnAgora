<?php

namespace spec\La\LearnodexBundle\Twig;

use GuzzleHttp\Client;
use GuzzleHttp\Message\MessageInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class OpenInPlaceExtensionSpec extends ObjectBehavior
{
    const URL = 'http://localhost';

    function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    function it_has_a_name()
    {
        $this->getName()->shouldBeString();
    }

    function it_has_filters()
    {
        $this->getFilters()->shouldBeArray();
    }

    function it_does_not_open_sites_that_have_deny_frame_options(Client $client, MessageInterface $response)
    {
        $response->getHeader('x-frame-options')->willReturn('deny');
        $client->head(self::URL)->willReturn($response);
        $this->canOpen(self::URL)->shouldBe(false);
    }

    function it_returns_false_on_sites_that_have_same_origin_frame_options(Client $client, MessageInterface $response)
    {
        $response->getHeader('x-frame-options')->willReturn('sameorigin');
        $client->head(self::URL)->willReturn($response);
        $this->canOpen(self::URL)->shouldBe(false);
    }

    function it_returns_false_on_sites_that_have_allow_from_frame_options(Client $client, MessageInterface $response)
    {
        $response->getHeader('x-frame-options')->willReturn('allow-from');
        $client->head(self::URL)->willReturn($response);
        $this->canOpen(self::URL)->shouldBe(false);
    }

    function it_checks_things_in_case_insensitive_ways(Client $client, MessageInterface $response)
    {
        $response->getHeader('x-frame-options')->willReturn('DENY');
        $client->head(self::URL)->willReturn($response);
        $this->canOpen(self::URL)->shouldBe(false);
    }

    function it_returns_true_on_sites_with_open_frame_options(Client $client, MessageInterface $response)
    {
        $response->getHeader('x-frame-options')->willReturn('');
        $client->head(self::URL)->willReturn($response);
        $this->canOpen(self::URL)->shouldBe(true);
    }
}
