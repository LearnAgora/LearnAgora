<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\LearnodexBundle\Model\Visitor;

use Doctrine\Common\Persistence\ObjectRepository;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\Action;
use La\CoreBundle\Entity\Objective;
use La\CoreBundle\Entity\LearningEntity;
use La\CoreBundle\Model\LearningEntity\GetTypeVisitor;
use La\CoreBundle\Visitor\ActionVisitorInterface;
use La\CoreBundle\Visitor\AgoraVisitorInterface;
use La\CoreBundle\Visitor\ObjectiveVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * @DI\Service("la_core.uplink_manager_visitor")
 */
class UpLinkManagerVisitor implements
    VisitorInterface,
    AgoraVisitorInterface,
    ObjectiveVisitorInterface,
    ActionVisitorInterface
{
    private $learningEntity = null;

    private $canHave;
    private $canSuggestTo;

    private $upLinks = null;
    private $downLinks = null;
    private $parentLinks;
    private $childLinks;

    /**
     * @var ObjectRepository
     */
    private $agoraRepository;
    /**
     * @var ObjectRepository
     */
    private $objectiveRepository;
    /**
     * @var ObjectRepository
     */
    private $actionRepository;

    /**
     * Constructor
     *
     * @param ObjectRepository $agoraRepository
     * @param ObjectRepository $objectiveRepository
     * @param ObjectRepository $actionRepository
     *
     * @DI\InjectParams({
     *  "agoraRepository" = @DI\Inject("la_core.repository.agora"),
     *  "objectiveRepository" = @DI\Inject("la_core.repository.objective"),
     *  "actionRepository" = @DI\Inject("la_core.repository.action")
     * })
     */
    public function __construct(ObjectRepository $agoraRepository, ObjectRepository $objectiveRepository, ObjectRepository $actionRepository) {
        $this->agoraRepository = $agoraRepository;
        $this->objectiveRepository = $objectiveRepository;
        $this->actionRepository = $actionRepository;
        $this->parentLinks = array(
            'Agora' => null,
            'Objective' => null,
            'Action' => null
        );
        $this->childLinks = array(
            'Agora' => null,
            'Objective' => null,
            'Action' => null
        );
        $this->canHave = array(
            'Agora' => false,
            'Objective' => false,
            'Action' => false
        );
        $this->canSuggestTo = array(
            'Agora' => false,
            'Objective' => false,
            'Action' => false
        );
    }
    /**
     * {@inheritdoc}
     */
    public function visitAgora(Agora $agora)
    {
        $this->learningEntity = $agora;
        $this->canHave['Objective'] = true;
        $this->canHave['Action'] = true;
    }

    /**
     * {@inheritdoc}
     */
    public function visitObjective(Objective $objective)
    {
        $this->learningEntity = $objective;
        $this->canSuggestTo['Agora'] = true;
        $this->canHave['Action'] = true;
    }
    /**
     * {@inheritdoc}
     */
    public function visitAction(Action $action)
    {
        $this->learningEntity = $action;
        $this->canSuggestTo['Agora'] = true;
        $this->canSuggestTo['Objective'] = true;
    }

    public function canHave($learningEntityType) {
        return $this->canHave[$learningEntityType];
    }
    public function canSuggestTo($learningEntityType){
        return $this->canSuggestTo[$learningEntityType];
    }

    public function getParentLinks($learningEntityType) {
        if (is_null($this->parentLinks[$learningEntityType])) {
            $this->parentLinks[$learningEntityType] = $this->filterUpLinks($learningEntityType);
        }
        return $this->parentLinks[$learningEntityType];
    }
    public function getChildLinks($learningEntityType) {
        if (is_null($this->childLinks[$learningEntityType])) {
            $this->childLinks[$learningEntityType] = $this->filterDownLinks($learningEntityType);
        }
        return $this->childLinks[$learningEntityType];
    }

    public function getUnusedParentEntities($learningEntityType) {
        return $this->getUnusedEntities($this->getEntities($learningEntityType),$this->getParentLinks($learningEntityType),true);
    }
    public function getUnusedChildEntities($learningEntityType) {
        return $this->getUnusedEntities($this->getEntities($learningEntityType),$this->getChildLinks($learningEntityType),false);
    }
    private function getEntities($learningEntityType) {
        switch ($learningEntityType) {
            case 'Agora' : return $this->agoraRepository->findAll();
                break;
            case 'Objective' : return $this->objectiveRepository->findAll();
                break;
            case 'Action' : return $this->actionRepository->findAll();
                break;
        }
    }
    private function getUnusedEntities($allEntities,$usedEntityLinks,$useParent)
    {
        $usedIds = array();
        foreach ($usedEntityLinks as $link) {
            $learningEntity = $useParent ? $link->getParent() : $link->getChild();
            $usedIds[] = $learningEntity->getId();
        }
        $unusedEntities = array();
        foreach ($allEntities as $entity) {
            if (!in_array($entity->getId(),$usedIds)) {
                $unusedEntities[] = $entity;
            }
        }
        return $unusedEntities;
    }

    private function loadUpLinks() {
        $this->upLinks = $this->learningEntity->getUplinks();
    }
    private function loadDownLinks() {
        $this->downLinks = $this->learningEntity->getDownlinks();
    }
    private function filterUpLinks($filter) {
        if (is_null($this->upLinks)) {
            $this->loadUpLinks();
        }
        return $this->filterLinks($this->upLinks,$filter,true);
    }
    private function filterDownLinks($filter) {
        if (is_null($this->downLinks)) {
            $this->loadDownLinks();
        }
        return $this->filterLinks($this->downLinks,$filter,false);
    }
    private function filterLinks($links,$filter,$useParent) {
        $filteredLinks = array();
        $getTypeVisitor = new GetTypeVisitor();
        foreach ($links as $link) {
            $learningEntity = $useParent ? $link->getParent() : $link->getChild();
            if ($learningEntity->accept($getTypeVisitor) == $filter) {
                $filteredLinks[] = $link;
            }
        }
        return $filteredLinks;
    }

}
