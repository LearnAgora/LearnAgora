<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Visitor\UrlContentVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * Objective
 */
class UrlContent extends Content
{
    private $instruction;
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
