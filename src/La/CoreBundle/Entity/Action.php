<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Model\ParticleVisitorInterface;

/**
 * Action
 */
class Action extends Particle
{
    public function accept(ParticleVisitorInterface $visitor) {
        $visitor->visitAction($this);
    }

}
