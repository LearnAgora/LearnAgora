<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * Logos
 */
class Logos extends AgoraBase
{
    public function accept(VisitorInterface $visitor)
    {
        return null;
    }

}
