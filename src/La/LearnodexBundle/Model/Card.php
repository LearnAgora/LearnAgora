<?php

namespace La\LearnodexBundle\Model;

use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;
use La\CoreBundle\Entity\LearningEntity;
use La\CoreBundle\Model\LearningEntity\CanHaveObjectivesVisitor;
use La\CoreBundle\Model\LearningEntity\GetTypeVisitor;
use La\CoreBundle\Model\PossibleOutcomeVisitor;
use La\LearnodexBundle\Model\Visitor\GetContentIncludeTwigVisitor;
use La\LearnodexBundle\Model\Visitor\GetContentTwigVisitor;

/**
 * @Serializer\ExclusionPolicy("all")
 *
 * @Hateoas\Relation("self", href = "expr('/sandbox/card/' ~ object.getLearningEntity().getId())")
 * @Hateoas\Relation("random", href = "expr('/sandbox/random')")
 * @Hateoas\Relation(
 *     "learning-entity",
 *     href = "expr('/sandbox/learning-entity/' ~ object.getLearningEntity().getId())",
 *     embedded = "expr(object.getLearningEntity())",
 *     exclusion = @Hateoas\Exclusion(excludeIf = "expr(object.getLearningEntity() === null)")
 * )
 */
class Card
{
    /**
     * @var LearningEntity
     */
    protected $learningEntity;
    protected $downLinks = null;
    protected $objectiveDownLinks = null;

    /**
     * @param LearningEntity $learningEntity
     **/
    public function __construct(LearningEntity $learningEntity)
    {
        $this->learningEntity = $learningEntity;
    }

    public function getLearningEntity()
    {
        return $this->learningEntity;
    }
    public function getType() {
        $getTypeVisitor = new GetTypeVisitor();
        return $this->learningEntity->accept($getTypeVisitor);
    }
    public function getId()
    {
        return $this->learningEntity->getId();
    }
    public function getName()
    {
        return $this->learningEntity->getName();
    }
    public function getContent()
    {
        return $this->learningEntity->getContent();
    }

    public function getContentTwig()
    {
        $getContentTwigVisitor = new GetContentTwigVisitor();
        return $this->learningEntity->accept($getContentTwigVisitor);
    }
    public function getContentIncludeTwig()
    {
        $getContentIncludeTwigVisitor = new GetContentIncludeTwigVisitor();
        return $this->learningEntity->accept($getContentIncludeTwigVisitor);
    }

    public function getOutcomes()
    {
        $outcomes = $this->learningEntity->getOutcomes();
        $possibleOutcomeVisitor = new PossibleOutcomeVisitor();
        $possibleOutcomes = $this->learningEntity->accept($possibleOutcomeVisitor);

        $cardOutcomes = array();
        foreach ($possibleOutcomes as $possibleOutcome) {
            $cardOutcome = new CardOutcome($possibleOutcome);
            foreach ($outcomes as $outcome) {
                $cardOutcome->addOutcome($outcome);
            }
            $cardOutcomes[] = $cardOutcome;
        }
        return $cardOutcomes;
    }

    public function canHaveObjectives() {
        $canHaveObjectivesVisitor = new CanHaveObjectivesVisitor();
        return $this->learningEntity->accept($canHaveObjectivesVisitor);
    }

    private function loadDownLinks() {
        $this->downLinks = $this->learningEntity->getDownlinks();
    }
    public function getObjectiveDownLinks() {
        if (is_null($this->downLinks)) {
            $this->loadDownLinks();
        }

        $this->objectiveDownLinks = array();
        $getTypeVisitor = new GetTypeVisitor();
        foreach ($this->downLinks as $downLink) {
            if ($downLink->getChild()->accept($getTypeVisitor) == "Objective") {
                $this->objectiveDownLinks[] = $downLink;
            }
        }

        return $this->objectiveDownLinks;
    }
    public function getCandidateObjectives() {
        if (is_null($this->objectiveDownLinks)) {
            $this->getObjectiveDownLinks();
        }
        $allObjectives = $this->em->getRepository('LaCoreBundle:Objective')->findAll();
        return $this->getUnusedEntities($allObjectives,$this->objectiveDownLinks);

    }

    private function getUnusedEntities($allEntities,$usedEntities)
    {
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

}
