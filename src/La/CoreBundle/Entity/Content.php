<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Content
 */
abstract class Content
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $text = "";

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Content
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

}
