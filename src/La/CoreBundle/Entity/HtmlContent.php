<?php

namespace La\CoreBundle\Entity;

use JMS\Serializer\Annotation as Serializer;
use La\CoreBundle\Visitor\HtmlContentVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * @Serializer\ExclusionPolicy("all")
 */
class HtmlContent extends Content
{
    /**
     * @var string
     *
     * @Serializer\Expose
     */
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

    public function accept(VisitorInterface $visitor)
    {
        if ($visitor instanceof HtmlContentVisitorInterface) {
            return $visitor->visitHtmlContent($this);
        }

        return null;
    }

    public function init($em = null)
    {
        $this->content = '';
    }
}
