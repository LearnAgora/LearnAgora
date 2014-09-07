<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Model\ContentVisitorInterface;

/**
 * Objective
 */
class QuizContent extends Content
{
    public function accept(ContentVisitorInterface $visitor) {
        return $visitor->visitQuizContent($this);
    }
    public function init() {

    }

}
