<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Visitor\AgoraGoalVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;

/**
 * @Serializer\ExclusionPolicy("all")
 *
 * @Hateoas\Relation(
 *     "agora",
 *     embedded = "expr(object.getAgora())",
 *     exclusion = @Hateoas\Exclusion(excludeIf = "expr(object.getAgora() === null)")
 * )
 */
class AgoraGoal extends Goal
{
    /**
     * @var Agora
     */
    private $agora;


    /**
     * Set agora
     *
     * @param Agora $agora
     * @return AgoraGoal
     */
    public function setAgora(Agora $agora = null)
    {
        $this->agora = $agora;

        return $this;
    }

    /**
     * Get agora
     *
     * @return Agora
     */
    public function getAgora()
    {
        return $this->agora;
    }

    public function accept(VisitorInterface $visitor)
    {
        if ($visitor instanceof AgoraGoalVisitorInterface) {
            return $visitor->visitAgoraGoal($this);
        }

        return null;
    }

}
