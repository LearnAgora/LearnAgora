<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Model\ContentVisitorInterface;


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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    abstract function accept(ContentVisitorInterface $visitor);

    abstract function init();

    public function getClassName()
    {
        $className = explode("\\",get_class($this));
        $className = $className[count($className)-1];
        return $className;
    }
}
