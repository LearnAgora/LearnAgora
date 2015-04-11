<?php

namespace spec\La\SecurityBundle\Entity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AuthCodeSpec extends ObjectBehavior
{
    function it_is_an_auth_code()
    {
        $this->shouldHaveType('FOS\OAuthServerBundle\Entity\AuthCode');
    }
}
