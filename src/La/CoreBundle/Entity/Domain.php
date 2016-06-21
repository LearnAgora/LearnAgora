<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Visitor\DomainVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

class Domain extends AgoraBase
{
    public function accept(VisitorInterface $visitor)
    {
        if ($visitor instanceof DomainVisitorInterface) {
            return $visitor->visitDomain($this);
        }

        return null;
    }

}
