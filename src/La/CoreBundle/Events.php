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

    const TRACE_CREATED = 'trace.created';
    const MISSING_USER_PROBABILITY = 'userProbability.missing';
    const MISSING_OUTCOME_PROBABILITY = 'outcomeProbability.missing';
    const LEARNING_ENTITY_CHANGED = 'learningEntity.changed';
    const USER_PROBABILITY_UPDATED = 'userProbability.updated';
}
