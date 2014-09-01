<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Model\ParticleVisitorInterface;

/**
 * Agora
 */
class Agora extends Particle
{
    public function accept(ParticleVisitorInterface $visitor) {
        $visitor->visitAgora($this);
    }
}
