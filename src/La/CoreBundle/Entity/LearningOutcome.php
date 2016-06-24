<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Visitor\LearningOutcomeVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * Outcome
 */
class LearningOutcome extends Outcome
{
    /**
     * @var integer
     */
    private $status;

    /**
     * @var Lom
     */
    private $lom;

    /**
     * @param $status
     * @return LearningOutcome
     */
    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @param $lom Lom
     * @return LearningOutcome
     */
    public function setLom($lom) {
        $this->lom = $lom;
        return $this;
    }

    /**
     * @return Lom
     */
    public function getLom() {
        return $this->lom;
    }
    public function accept(VisitorInterface $visitor)
    {
        if ($visitor instanceof LearningOutcomeVisitorInterface) {
            return $visitor->visitLearningOutcome($this);
        }

        return null;
    }
}
