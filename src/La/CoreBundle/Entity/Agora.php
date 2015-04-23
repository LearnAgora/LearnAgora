<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;
use La\CoreBundle\Visitor\AgoraVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * @Serializer\ExclusionPolicy("all")
 *
 */

class Agora extends AgoraBase
{
    public function accept(VisitorInterface $visitor)
    {
        if ($visitor instanceof AgoraVisitorInterface) {
            return $visitor->visitAgora($this);
        }

        return null;
    }

}
