<?php

namespace La\CoreBundle\Entity;

use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;
use La\CoreBundle\Visitor\VisitableInterface;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * @Serializer\ExclusionPolicy("all")
 *
 * @Hateoas\Relation("self", href = "expr('/sandbox/content/' ~ object.getId())")
 */
abstract class Content implements VisitableInterface
{
    /**
     * @var integer
     *
     * @Serializer\Expose
     */
    private $id;

    /**
     * Get id
     *
     * @return integer
     */
    private $duration;

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

    /**
     * Set duration
     *
     * @param integer $duration
     * @return Content
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return integer 
     */
    public function getDuration()
    {
        return $this->duration;
    }
}
