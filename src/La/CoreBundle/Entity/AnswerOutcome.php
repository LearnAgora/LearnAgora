<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Visitor\AnswerOutcomeVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * Outcome
 */
class AnswerOutcome extends Outcome
{
    /**
     * @var integer
     */
    private $selected;

    private $answer;

    /**
     * Set selected
     *
     * @param integer $selected
     * @return AnswerOutcome
     */
    public function setSelected($selected)
    {
        $this->selected = $selected;

        return $this;
    }

    /**
     * Get selected
     *
     * @return integer
     */
    public function getSelected()
    {
        return $this->selected;
    }

    /**
     * Set answer
     *
     * @param \La\CoreBundle\Entity\Answer $answer
     * @return AnswerOutcome
     */
    public function setAnswer(\La\CoreBundle\Entity\Answer $answer = null)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return \La\CoreBundle\Entity\Answer
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    public function accept(VisitorInterface $visitor)
    {
        if ($visitor instanceof AnswerOutcomeVisitorInterface) {
            return $visitor->visitAnswerOutcome($this);
        }

        return null;
    }
}
