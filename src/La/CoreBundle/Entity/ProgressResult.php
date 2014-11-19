<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Visitor\ProgressResultVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * AffinityResult
 */
class ProgressResult extends Result
{
    public function accept(VisitorInterface $visitor)
    {
        if ($visitor instanceof ProgressResultVisitorInterface) {
            return $visitor->visitProgressResult($this);
        }

        return null;
    }
}
