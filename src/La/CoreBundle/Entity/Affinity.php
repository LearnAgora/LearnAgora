<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;

/**
 * @Serializer\ExclusionPolicy("all")
 *
 */
class Affinity
{
    /**
     * @var integer
     *
     * @Serializer\Expose
     */
    private $id;

    /**
     * @var string
     *
     * @Serializer\Expose
     */
    private $value;

    /**
     * @var \La\CoreBundle\Entity\User
     */
    private $user;

    /**
     * @var \La\CoreBundle\Entity\AgoraBase
     *
     * @Serializer\Expose
     */
    private $agora;

    /**
     * @var \La\CoreBundle\Entity\Profile
     *
     * @Serializer\Expose
     */
    private $profile;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return Affinity
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return Affinity
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set agora
     *
     * @param AgoraBase $agora
     * @return Affinity
     */
    public function setAgora(AgoraBase $agora = null)
    {
        $this->agora = $agora;

        return $this;
    }

    /**
     * Get agora
     *
     * @return AgoraBase
     */
    public function getAgora()
    {
        return $this->agora;
    }

    /**
     * Set profile
     *
     * @param Profile $profile
     * @return Affinity
     */
    public function setProfile(Profile $profile = null)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile
     *
     * @return Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }
}
