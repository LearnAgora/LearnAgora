<?php
/**
 * Created by PhpStorm.
 * User: Mihai
 * Date: 07/09/14
 * Time: 12:57
 */

namespace La\CoreBundle\Visitor;

use La\CoreBundle\Entity\Techne;

interface TechneVisitorInterface
{
    /**
     * @param Techne $learningEntity
     **/
    public function visitTechne(Techne $learningEntity);
}
