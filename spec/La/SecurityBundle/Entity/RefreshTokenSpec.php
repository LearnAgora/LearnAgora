<?php

namespace spec\La\SecurityBundle\Entity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RefreshTokenSpec extends ObjectBehavior
{
    function it_is_a_refresh_token()
    {
        $this->shouldHaveType('FOS\OAuthServerBundle\Entity\RefreshToken');
    }
}
