<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Model\ParticleVisitorInterface;

/**
 * Objective
 */
class Objective extends Particle
{
    public function accept(ParticleVisitorInterface $visitor) {
        $visitor->visitObjective($this);
    }

}
