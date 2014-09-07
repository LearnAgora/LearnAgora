<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Visitor\QuestionContentVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * Objective
 */
class QuestionContent extends Content
{
    private $instruction;

    public function accept(VisitorInterface $visitor) {
        if ($visitor instanceof QuestionContentVisitorInterface) {
            return $visitor->visitQuestionContent($this);
        }

        return null;
    }

    public function init() {
        $this->instruction = '';
    }


    /**
     * Set instruction
     *
     * @param string $instruction
     * @return QuestionContent
     */
    public function setInstruction($instruction)
    {
        $this->instruction = $instruction;

        return $this;
    }

    /**
     * Get instruction
     *
     * @return string 
     */
    public function getInstruction()
    {
        return $this->instruction;
    }
}
