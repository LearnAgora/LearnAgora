<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Visitor\ObjectiveVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * Objective
 */
class Objective extends LearningEntity
{
    public function accept(VisitorInterface $visitor) {
        if ($visitor instanceof ObjectiveVisitorInterface) {
            return $visitor->visitObjective($this);
        }

        return null;
    }

}
