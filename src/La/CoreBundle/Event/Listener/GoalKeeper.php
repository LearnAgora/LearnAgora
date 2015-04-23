<?php

namespace La\CoreBundle\Event\Listener;

use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Event\GoalEvent;
use La\CoreBundle\Event\PersonaMatchEvent;
use La\CoreBundle\Events;


/**
 * @DI\Service
 */
class GoalKeeper
{

    /**
     * @DI\Observe(Events::USER_GOAL_UPDATE)
     *
     * @param GoalEvent $event
     */
    public function onGoalUpdate(GoalEvent $event)
    {
    }

    /**
     * @DI\Observe(Events::USER_PERSONA_MATCH_UPDATE)
     *
     * @param PersonaMatchEvent $event
     */
    public function onPersonaMatchUpdate(PersonaMatchEvent $event)
    {
    }
}
