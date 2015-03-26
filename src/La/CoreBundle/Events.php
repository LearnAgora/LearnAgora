<?php

namespace La\CoreBundle;

final class Events
{
    const TRACE_CREATED = 'trace.created';
    const MISSING_USER_PROBABILITY = 'userProbability.missing';
    const MISSING_OUTCOME_PROBABILITY = 'outcomeProbability.missing';
    const LEARNING_ENTITY_CHANGED = 'learningEntity.changed';
}
