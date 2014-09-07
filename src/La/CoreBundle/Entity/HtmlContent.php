<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Model\ContentVisitorInterface;


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

    public function accept(ContentVisitorInterface $visitor) {
        return $visitor->visitHtmlContent($this);
    }

    public function init() {
        $this->content = '';
    }

}
