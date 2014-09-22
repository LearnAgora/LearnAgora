<?php

namespace La\CoreBundle\Entity;

use JMS\Serializer\Annotation as Serializer;
use La\CoreBundle\Visitor\QuizContentVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * @Serializer\ExclusionPolicy("all")
 */
class QuizContent extends Content
{
    public function accept(VisitorInterface $visitor) {
        if ($visitor instanceof QuizContentVisitorInterface) {
            return $visitor->visitQuizContent($this);
        }

        return null;
    }

    public function init($em = null) {

    }
}
