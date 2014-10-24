<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Visitor\AgoraVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * Agora
 */
class Agora extends LearningEntity
{
    public function accept(VisitorInterface $visitor)
    {
        if ($visitor instanceof AgoraVisitorInterface) {
            return $visitor->visitAgora($this);
        }

        return null;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $affinities;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->affinities = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add affinities
     *
     * @param \La\CoreBundle\Entity\Affinity $affinities
     * @return Agora
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
