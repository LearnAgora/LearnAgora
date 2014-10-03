<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\LearnodexBundle\Model\Visitor;

use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\Action;
use La\CoreBundle\Entity\Objective;
use La\CoreBundle\Entity\LearningEntity;
use La\CoreBundle\Visitor\ActionVisitorInterface;
use La\CoreBundle\Visitor\AgoraVisitorInterface;
use La\CoreBundle\Visitor\ObjectiveVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

class UpLinkManagerVisitor implements
    VisitorInterface,
    AgoraVisitorInterface,
    ObjectiveVisitorInterface,
    ActionVisitorInterface
{

    private $em = null;
    private $parents = null;
    private $candidateParents = null;
    private $canHaveParents = false;
    private $children = null;
    private $candidateChildren = null;
    private $canHaveChildren = null;


    public function __construct($em)
    {
        $this->em = $em;
    }
    private function loadParents(LearningEntity $learningEntity)
    {
        $upLinks = $learningEntity->getUplinks();
        $this->parents = array();
        foreach ($upLinks as $upLink) {
            $this->parents[] = $upLink->getParent();
        }
    }
    private function loadChildren(LearningEntity $learningEntity)
    {
        $downLinks = $learningEntity->getDownlinks();
        $this->children = array();
        foreach ($downLinks as $downLink) {
            $this->children[] = $downLink->getChild();
        }
    }
    public function canHaveParents() {
        return $this->canHaveParents;
    }
    public function canHaveChildren() {
        return $this->canHaveChildren;
    }
    public function getCandidateChildren(){
        return $this->candidateChildren;
    }
    public function getCandidateParents(){
        return $this->candidateParents;
    }
    public function getUnusedEntities($allEntities,$usedEntities) {

        $usedIds = array();
        foreach ($usedEntities as $usedEntity) {
            $usedIds[] = $usedEntity->getId();
        }
        $unusedEntities = array();
        foreach ($allEntities as $entity) {
            if (!in_array($entity->getId(),$usedIds)) {
                $unusedEntities[] = $entity;
            }
        }
        return $unusedEntities;
    }
    /**
     * {@inheritdoc}
     */
    public function visitAgora(Agora $agora)
    {
        if (is_null($this->children)) {
            $this->loadChildren($agora);
        }

        $allActions = $this->em->getRepository('LaCoreBundle:Action')->findAll();
        $this->candidateChildren = $this->getUnusedEntities($allActions,$this->children);
        $this->canHaveChildren = true;
    }

    /**
     * {@inheritdoc}
     */
    public function visitObjective(Objective $objective)
    {
    }
    /**
     * {@inheritdoc}
     */
    public function visitAction(Action $action)
    {
        if (is_null($this->parents)){
            $this->loadParents($action);
        }
        $allAgoras = $this->em->getRepository('LaCoreBundle:Agora')->findAll();
        $this->candidateParents = $this->getUnusedEntities($allAgoras,$this->parents);
        $this->canHaveParents = true;
    }

}