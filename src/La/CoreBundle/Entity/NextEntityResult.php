<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * NextEntityResult
 */
class NextEntityResult extends Result
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

    public function accept(VisitorInterface $visitor) {
        //if ($visitor instanceof ObjectiveVisitorInterface) {
        //    return $visitor->visitObjective($this);
        //}

        return null;
    }

}
