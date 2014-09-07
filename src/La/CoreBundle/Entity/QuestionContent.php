<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Model\ContentVisitorInterface;

/**
 * Objective
 */
class QuestionContent extends Content
{
    private $instruction;

    public function accept(ContentVisitorInterface $visitor) {
        return $visitor->visitQuestionContent($this);
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
