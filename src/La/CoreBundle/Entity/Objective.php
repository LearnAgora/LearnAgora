<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Model\LearningEntityVisitorInterface;

/**
 * Objective
 */
class Objective extends LearningEntity
{
    public function accept(LearningEntityVisitorInterface $visitor) {
        return $visitor->visitObjective($this);
    }

}
