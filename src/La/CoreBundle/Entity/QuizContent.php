<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Visitor\QuizContentVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * Objective
 */
class QuizContent extends Content
{
    public function accept(VisitorInterface $visitor) {
        if ($visitor instanceof QuizContentVisitorInterface) {
            return $visitor->visitQuizContent($this);
        }

        return null;
    }

    public function init() {

    }
}
