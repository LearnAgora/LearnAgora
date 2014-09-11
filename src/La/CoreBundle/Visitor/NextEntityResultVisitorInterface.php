<?php
/**
 * Created by PhpStorm.
 * User: Mihai
 * Date: 07/09/14
 * Time: 13:59
 */

namespace La\CoreBundle\Visitor;


use La\CoreBundle\Entity\NextEntityResult;

interface NextEntityResultVisitorInterface {
    /**
     * @param NextEntityResult $result
     **/
    public function visitNextEntityResult(NextEntityResult $result);
} 