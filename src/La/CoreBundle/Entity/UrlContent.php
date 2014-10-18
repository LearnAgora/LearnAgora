<?php

namespace La\CoreBundle\Entity;

use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;
use La\CoreBundle\Visitor\UrlContentVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * @Serializer\ExclusionPolicy("all")
 *
 * @Hateoas\Relation("self", href = @Hateoas\Route("get_url-content", parameters = { "id" = "expr(object.getId())" }))
 */
class UrlContent extends Content
{
    /**
     * @var string
     *
     * @Serializer\Expose
     */
    private $instruction;

    /**
     * @var string
     *
     * @Serializer\Expose
     */
    private $url;

    public function accept(VisitorInterface $visitor) {
        if ($visitor instanceof UrlContentVisitorInterface) {
            return $visitor->visitUrlContent($this);
        }

        return null;
    }

    public function init($em = null) {
        $this->instruction = '';
        $this->url = '';
    }

    /**
     * Set instruction
     *
     * @param string $instruction
     * @return UrlContent
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

    /**
     * Set url
     *
     * @param string $url
     * @return UrlContent
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}
