<?php

namespace La\CoreBundle\Entity;

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
     * @var AgoraBase
     */
    private $agora;

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->agora->getName();
    }

    /**
     * Set agora
     *
     * @param AgoraBase $agora
     *
     * @return AgoraGoal
     */
    public function setAgora(AgoraBase $agora = null)
    {
        $this->agora = $agora;

        return $this;
    }

    /**
     * Get agora
     *
     * @return AgoraBase
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
