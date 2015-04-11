<?php

namespace La\CoreBundle\Event;

use La\CoreBundle\Entity\PersonaMatch;
use Symfony\Component\EventDispatcher\Event;

class PersonaMatchEvent extends Event
{
    /**
     * @var PersonaMatch
     */
    private $personaMatch;

    /**
     * Constructor.
     *
     * @param PersonaMatch $personaMatch
     */
    public function __construct(PersonaMatch $personaMatch)
    {
        $this->personaMatch = $personaMatch;
    }

    /**
     * @return PersonaMatch
     */
    public function getPersonaMatch()
    {
        return $this->personaMatch;
    }
}
