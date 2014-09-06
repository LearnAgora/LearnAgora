<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\CoreBundle\Model;


use La\CoreBundle\Entity\Action;
use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\HtmlContent;
use La\CoreBundle\Entity\Objective;

class ContentVisitor implements LearningEntityVisitorInterface
{
    /**
     * {@inheritdoc}
     */
    public function visitAgora(Agora $agora)
    {
        return new HtmlContent();
    }

    /**
     * {@inheritdoc}
     */
    public function visitObjective(Objective $objective)
    {
        return new HtmlContent();
    }

    /**
     * {@inheritdoc}
     */
    public function visitAction(Action $action)
    {
        return new HtmlContent();
    }
} 