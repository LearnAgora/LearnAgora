<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Visitor\PersonaGoalVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * Goal
 */
class PersonaGoal extends Goal
{
    /**
     * @var Persona
     */
    private $persona;


    /**
     * Set persona
     *
     * @param Persona $persona
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
