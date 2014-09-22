<?php

namespace La\CoreBundle\Entity;

use JMS\Serializer\Annotation as Serializer;
use La\CoreBundle\Visitor\MultipleChoiceQuestionVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * @Serializer\ExclusionPolicy("all")
 */
class MultipleChoiceQuestion extends QuestionContent
{
    public function accept(VisitorInterface $visitor) {
        if ($visitor instanceof MultipleChoiceQuestionVisitorInterface) {
            return $visitor->visitMultipleChoiceQuestion($this);
        }

        return null;
    }
}
