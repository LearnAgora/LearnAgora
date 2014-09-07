<?php
/**
 * Created by PhpStorm.
 * User: Mihai
 * Date: 07/09/14
 * Time: 13:00
 */

namespace La\CoreBundle\Visitor;


use La\CoreBundle\Entity\Action;

interface ActionVisitorInterface {
    /**
     * @param Action $learningEntity
     **/
    public function visitAction(Action $learningEntity);
} 