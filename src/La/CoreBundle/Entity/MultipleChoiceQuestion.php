<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Visitor\MultipleChoiceQuestionVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * MultipleChoiceQuestion
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
