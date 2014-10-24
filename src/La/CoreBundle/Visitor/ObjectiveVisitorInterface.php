<?php
/**
 * Created by PhpStorm.
 * User: Mihai
 * Date: 07/09/14
 * Time: 12:56
 */

namespace La\CoreBundle\Visitor;

use La\CoreBundle\Entity\Objective;

interface ObjectiveVisitorInterface
{
    /**
     * @param Objective $learningEntity
     **/
    public function visitObjective(Objective $learningEntity);
}
