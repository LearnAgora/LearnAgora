<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Visitor\UrlOutcomeVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * UrlOutcome
 */
class UrlOutcome extends Outcome
{
    public function accept(VisitorInterface $visitor)
    {
        if ($visitor instanceof UrlOutcomeVisitorInterface) {
            return $visitor->visitUrlOutcome($this);
        }

        return null;
    }
}
