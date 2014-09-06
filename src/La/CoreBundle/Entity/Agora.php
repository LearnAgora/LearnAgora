<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Model\LearningEntityVisitorInterface;

/**
 * Agora
 */
class Agora extends LearningEntity
{
    public function accept(LearningEntityVisitorInterface $visitor) {
        return $visitor->visitAgora($this);
    }
}
