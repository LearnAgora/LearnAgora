<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Model\LearningEntityVisitorInterface;

/**
 * Objective
 */
class Objective extends LearningEntity
{
    public function accept(LearningEntityVisitorInterface $visitor) {
        return $visitor->visitObjective($this);
    }

    /**
     * @var \La\CoreBundle\Entity\HtmlContent
     */
    private $content;


    /**
     * Set content
     *
     * @param \La\CoreBundle\Entity\HtmlContent $content
     * @return Objective
     */
    public function setContent(\La\CoreBundle\Entity\HtmlContent $content = null)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return \La\CoreBundle\Entity\HtmlContent 
     */
    public function getContent()
    {
        return $this->content;
    }
}
