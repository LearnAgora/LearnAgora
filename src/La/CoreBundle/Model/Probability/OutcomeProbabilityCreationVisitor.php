<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\CoreBundle\Model;

use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Action;
use La\CoreBundle\Visitor\ActionVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * @DI\Service
 */
class OutcomeProbabilityCreationVisitor implements
    VisitorInterface,
    ActionVisitorInterface

{
    /**
     * {@inheritdoc}
     */
    public function visitAction(Action $action)
    {

    }


}
