<?php

namespace La\CoreBundle\Entity;

use La\CoreBundle\Visitor\PersonaGoalVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;

/**
 * @Serializer\ExclusionPolicy("all")
 *
 * @Hateoas\Relation(
 *     "persona",
 *     embedded = "expr(object.getPersona())",
 *     exclusion = @Hateoas\Exclusion(excludeIf = "expr(object.getPersona() === null)")
 * )
 */
class PersonaGoal extends Goal
{
    /**
     * @var Persona
     */
    private $persona;

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->persona->getUsername();
    }

    /**
     * Set persona
     *
     * @param Persona $persona
     *
     * @return PersonaGoal
     */
    public function setPersona(Persona $persona = null)
    {
        $this->persona = $persona;

        return $this;
    }

    /**
     * Get persona
     *
     * @return Persona
     */
    public function getPersona()
    {
        return $this->persona;
    }

    public function accept(VisitorInterface $visitor)
    {
        if ($visitor instanceof PersonaGoalVisitorInterface) {
            return $visitor->visitPersonaGoal($this);
        }

        return null;
    }
}
