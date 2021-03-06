<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Visitor\TechneVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

class Techne extends AgoraBase
{
    public function accept(VisitorInterface $visitor)
    {
        if ($visitor instanceof TechneVisitorInterface) {
            return $visitor->visitTechne($this);
        }

        return null;
    }

}
