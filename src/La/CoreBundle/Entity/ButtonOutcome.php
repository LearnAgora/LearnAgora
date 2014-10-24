<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Visitor\ButtonOutcomeVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;


/**
 * ButtonOutcome
 */
class ButtonOutcome extends Outcome
{
    private $caption;

    public function accept(VisitorInterface $visitor)
    {
        if ($visitor instanceof ButtonOutcomeVisitorInterface) {
            return $visitor->visitButtonOutcome($this);
        }

        return null;
    }


    /**
     * Set caption
     *
     * @param string $caption
     * @return ButtonOutcome
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;

        return $this;
    }

    /**
     * Get caption
     *
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }
}
