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
    public function accept(VisitorInterface $visitor) {
        if ($visitor instanceof AgoraVisitorInterface) {
            return $visitor->visitAgora($this);
        }

        return null;
    }
}
