<?php

namespace La\SecurityBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\OAuthServerBundle\Entity\Client as BaseClient;

class Client extends BaseClient
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Collection
     */
    private $accessTokens;

    /**
     * @var Collection
     */
    private $refreshTokens;

    /**
     * @var Collection
     */
    private $authCodes;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->accessTokens = new ArrayCollection();
        $this->refreshTokens = new ArrayCollection();
        $this->authCodes = new ArrayCollection();
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param AccessToken $accessToken
     */
    public function addAccessToken(AccessToken $accessToken)
    {
        $this->accessTokens[] = $accessToken;
    }

    /**
     * @param AccessToken $accessToken
     */
    public function removeAccessToken(AccessToken $accessToken)
    {
        $this->accessTokens->removeElement($accessToken);
    }

    /**
     * @return AccessToken[]
     */
    public function getAccessTokens()
    {
        return $this->accessTokens;
    }

    /**
     * @param RefreshToken $refreshToken
     */
    public function addRefreshToken(RefreshToken $refreshToken)
    {
        $this->refreshTokens[] = $refreshToken;
    }

    /**
     * @param RefreshToken $refreshToken
     */
    public function removeRefreshToken(RefreshToken $refreshToken)
    {
        $this->refreshTokens->removeElement($refreshToken);
    }

    /**
     * @return RefreshToken[]
     */
    public function getRefreshTokens()
    {
        return $this->refreshTokens;
    }

    /**
     * @param AuthCode $authCode
     */
    public function addAuthCode(AuthCode $authCode)
    {
        $this->authCodes[] = $authCode;
    }

    /**
     * @param AuthCode $authCode
     */
    public function removeAuthCode(AuthCode $authCode)
    {
        $this->authCodes->removeElement($authCode);
    }

    /**
     * @return AuthCode[]
     */
    public function getAuthCodes()
    {
        return $this->authCodes;
    }
}
