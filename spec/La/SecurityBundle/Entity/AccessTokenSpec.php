<?php

namespace spec\La\SecurityBundle\Entity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AccessTokenSpec extends ObjectBehavior
{
    function it_is_an_access_token()
    {
        $this->shouldHaveType('FOS\OAuthServerBundle\Entity\AccessToken');
    }
}
