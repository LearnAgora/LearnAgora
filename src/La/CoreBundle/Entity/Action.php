<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Visitor\ActionVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * Action
 */
class Action extends LearningEntity
{
    public function accept(VisitorInterface $visitor) {
        if ($visitor instanceof ActionVisitorInterface) {
            return $visitor->visitAction($this);
        }

        return null;
    }
}
