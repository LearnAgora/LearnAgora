<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Visitor\VisitableInterface;
use La\CoreBundle\Visitor\VisitorInterface;


/**
 * Content
 */
abstract class Content implements VisitableInterface
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

    abstract function accept(VisitorInterface $visitor);

    abstract function init($em = null);

    public function getClassName()
    {
        $className = explode("\\",get_class($this));
        $className = $className[count($className)-1];
        return $className;
    }
}
