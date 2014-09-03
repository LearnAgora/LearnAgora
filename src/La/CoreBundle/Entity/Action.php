<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Model\LearningEntityVisitorInterface;

/**
 * Action
 */
class Action extends LearningEntity
{
    public function accept(LearningEntityVisitorInterface $visitor) {
        return $visitor->visitAction($this);
    }

}
