<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Model\ContentVisitorInterface;

/**
 * Objective
 */
class UrlContent extends Content
{
    private $instruction;
    private $url;

    public function accept(ContentVisitorInterface $visitor) {
        return $visitor->visitUrlContent($this);
    }
    public function init() {
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
