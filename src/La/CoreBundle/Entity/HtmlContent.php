<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Visitor\HtmlContentVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;


/**
 * Objective
 */
class HtmlContent extends Content
{
    private $content;

    /**
     * Set content
     *
     * @param string $content
     * @return HtmlContent
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    public function accept(VisitorInterface $visitor) {
        if ($visitor instanceof HtmlContentVisitorInterface) {
            return $visitor->visitHtmlContent($this);
        }

        return null;
    }

    public function init() {
        $this->content = '';
    }

}
