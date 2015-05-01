<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;
use La\CoreBundle\Visitor\TechneVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * @Serializer\ExclusionPolicy("all")
 *
 * @Hateoas\Relation(
 *     "content",
 *     embedded = "expr(object.getDownlinks())",
 *     exclusion = @Hateoas\Exclusion(excludeIf = "expr(object.getDownlinks() === null)")
 * )
 */
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
