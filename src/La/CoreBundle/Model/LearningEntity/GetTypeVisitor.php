<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\CoreBundle\Model\LearningEntity;

use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\Action;
use La\CoreBundle\Entity\Domain;
use La\CoreBundle\Entity\Objective;
use La\CoreBundle\Entity\Techne;
use La\CoreBundle\Visitor\ActionVisitorInterface;
use La\CoreBundle\Visitor\AgoraVisitorInterface;
use La\CoreBundle\Visitor\DomainVisitorInterface;
use La\CoreBundle\Visitor\ObjectiveVisitorInterface;
use La\CoreBundle\Visitor\TechneVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

class GetTypeVisitor implements VisitorInterface, DomainVisitorInterface, TechneVisitorInterface, AgoraVisitorInterface, ObjectiveVisitorInterface, ActionVisitorInterface
{
    /**
     * {@inheritdoc}
     */
    public function visitDomain(Domain $learningEntity)
    {
        return "Domain";
    }

    /**
     * {@inheritdoc}
     */
    public function visitTechne(Techne $learningEntity)
    {
        return "Techne";
    }

    /**
     * {@inheritdoc}
     */
    public function visitAgora(Agora $learningEntity)
    {
        return "Agora";
    }

    /**
     * {@inheritdoc}
     */
    public function visitObjective(Objective $learningEntity)
    {
        return "Objective";
    }

    /**
     * {@inheritdoc}
     */
    public function visitAction(Action $learningEntity)
    {
        return "Action";
    }
}
