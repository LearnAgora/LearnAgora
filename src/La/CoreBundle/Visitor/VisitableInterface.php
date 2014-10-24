<?php
/**
 * Created by PhpStorm.
 * User: Mihai
 * Date: 07/09/14
 * Time: 13:05
 */

namespace La\CoreBundle\Visitor;

interface VisitableInterface
{
    /**
     * @param VisitorInterface $visitor
     *
     * @return mixed
     */
    public function accept(VisitorInterface $visitor);
}
