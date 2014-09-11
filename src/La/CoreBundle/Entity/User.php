<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * User
 */
class User implements AdvancedUserInterface, \Serializable
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $email;

    /**
     * @var integer
     */
    private $isActive;

    public function __construct()
    {
        $this->isActive = true;
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid(null, true));
    }

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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set isActive
     *
     * @param integer $isActive
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return integer 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        //return array('ROLE_USER');
        return array('ROLE_ADMIN');
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $learningEntities;


    /**
     * Add learningEntities
     *
     * @param \La\CoreBundle\Entity\LearningEntity $learningEntities
     * @return User
     */
    public function addLearningEntity(\La\CoreBundle\Entity\LearningEntity $learningEntities)
    {
        $this->learningEntities[] = $learningEntities;

        return $this;
    }

    /**
     * Remove learningEntities
     *
     * @param \La\CoreBundle\Entity\LearningEntity $learningEntities
     */
    public function removeLearningEntity(\La\CoreBundle\Entity\LearningEntity $learningEntities)
    {
        $this->learningEntities->removeElement($learningEntities);
    }

    /**
     * Get learningEntities
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLearningEntities()
    {
        return $this->learningEntities;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $traces;


    /**
     * Add traces
     *
     * @param \La\CoreBundle\Entity\Trace $traces
     * @return User
     */
    public function addTrace(\La\CoreBundle\Entity\Trace $traces)
    {
        $this->traces[] = $traces;

        return $this;
    }

    /**
     * Remove traces
     *
     * @param \La\CoreBundle\Entity\Trace $traces
     */
    public function removeTrace(\La\CoreBundle\Entity\Trace $traces)
    {
        $this->traces->removeElement($traces);
    }

    /**
     * Get traces
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTraces()
    {
        return $this->traces;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $affinities;


    /**
     * Add affinities
     *
     * @param \La\CoreBundle\Entity\Affinity $affinities
     * @return User
     */
    public function addAffinity(\La\CoreBundle\Entity\Affinity $affinities)
    {
        $this->affinities[] = $affinities;

        return $this;
    }

    /**
     * Remove affinities
     *
     * @param \La\CoreBundle\Entity\Affinity $affinities
     */
    public function removeAffinity(\La\CoreBundle\Entity\Affinity $affinities)
    {
        $this->affinities->removeElement($affinities);
    }

    /**
     * Get affinities
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAffinities()
    {
        return $this->affinities;
    }
}
