<?php

namespace La\CoreBundle;

final class Events
{
    /**
     * This event is dispatched each time a trace is recorded.
     *
     * @see La\CoreBundle\Event\TraceEvent
     *
     * @var string
     */
    const TRACE_CREATE = 'trace.created';

    /**
     * This event is dispatched each time a user updates her goal.
     *
     * @see La\CoreBundle\Event\GoalEvent
     *
     * @var string
     */
    const USER_GOAL_UPDATE = 'user.goal.update';

    /**
     * This event is dispatched each time a user's persona match is updated.
     *
     * @see La\CoreBundle\Event\PersonaMatchEvent
     *
     * @var string
     */
    const USER_PERSONA_MATCH_UPDATE = 'user.personaMatch.update';
}
