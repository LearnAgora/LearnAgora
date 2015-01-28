<?php

namespace La\LearnodexBundle\Model;

use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;
use La\CoreBundle\Entity\LearningEntity;
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

    /**
     * @Serializer\Expose
     * @Serializer\Accessor(getter="getProgress")
     */
    protected $progress;

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
    public function isActionCard() {
        return $this->getType() == "Action";
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
            $cardOutcome->setOutcomeFromCollection($outcomes);
            $cardOutcomes[] = $cardOutcome;
        }
        return $cardOutcomes;
    }

    public function getProgress() {
        if ($this->progress) {
            return $this->progress->getValue();
        }
        return 0;
    }
    public function setProgress($progress){
        $this->progress = $progress;
    }
}
