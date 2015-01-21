<?php
/**
 * Created by PhpStorm.
 * User: Mihai
 * Date: 07/09/14
 * Time: 13:00
 */

namespace La\CoreBundle\Visitor;

use La\CoreBundle\Entity\PersonaGoal;

interface PersonaGoalVisitorInterface
{
    /**
     * @param PersonaGoal $goal
     **/
    public function visitPersonaGoal(PersonaGoal $goal);
}
