<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use La\CoreBundle\Visitor\AffinityResultVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * AffinityResult
 */
class AffinityResult extends Result
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

    public function accept(VisitorInterface $visitor)
    {
        if ($visitor instanceof AffinityResultVisitorInterface) {
            return $visitor->visitAffinityResult($this);
        }

        return null;
    }
}
