<?php
/**
 * Created by PhpStorm.
 * User: Mihai
 * Date: 07/09/14
 * Time: 13:59
 */

namespace La\CoreBundle\Visitor;

use La\CoreBundle\Entity\AffinityResult;

interface AffinityResultVisitorInterface
{
    /**
     * @param AffinityResult $result
     **/
    public function visitAffinityResult(AffinityResult $result);
}
