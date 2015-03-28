<?php

namespace spec\La\SecurityBundle\Entity;

use La\SecurityBundle\Entity\AccessToken;
use La\SecurityBundle\Entity\AuthCode;
use La\SecurityBundle\Entity\RefreshToken;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ClientSpec extends ObjectBehavior
{
    function it_is_an_oauth_client()
    {
        $this->shouldHaveType('FOS\OAuthServerBundle\Entity\Client');
    }

    function it_initializes_the_base_class()
    {
        $this->getRandomId()->shouldNotBeNull();
        $this->getSecret()->shouldNotBeNull();
    }

    function it_holds_a_collection_of_access_tokens(AccessToken $accessToken1, AccessToken $accessToken2)
    {
        $this->addAccessToken($accessToken1);
        $this->addAccessToken($accessToken2);
        $this->getAccessTokens()->shouldHaveCount(2);
        $this->removeAccessToken($accessToken2);
        $this->getAccessTokens()->shouldHaveCount(1);
    }

    function it_holds_a_collection_of_refresh_tokens(RefreshToken $refreshToken1, RefreshToken $refreshToken2)
    {
        $this->addRefreshToken($refreshToken1);
        $this->addRefreshToken($refreshToken2);
        $this->getRefreshTokens()->shouldHaveCount(2);
        $this->removeRefreshToken($refreshToken2);
        $this->getRefreshTokens()->shouldHaveCount(1);
    }

    function it_holds_a_collection_of_auth_codes(AuthCode $authCode1, AuthCode $authCode2)
    {
        $this->addAuthCode($authCode1);
        $this->addAuthCode($authCode2);
        $this->getAuthCodes()->shouldHaveCount(2);
        $this->removeAuthCode($authCode2);
        $this->getAuthCodes()->shouldHaveCount(1);
    }
}
