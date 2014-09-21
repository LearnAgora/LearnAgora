<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/9/14
 * Time: 9:10 AM
 */

namespace La\LearnodexBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use La\CoreBundle\Entity\LearningEntity;
use La\CoreBundle\Model\PossibleOutcomeVisitor;
use La\LearnodexBundle\Model\Visitor\GetContentIncludeTwigVisitor;
use La\LearnodexBundle\Model\Visitor\GetContentTwigVisitor;

/**
 * @Serializer\ExclusionPolicy("all")
 */
class Card
{
    /**
     * @var LearningEntity
     *
     * @Serializer\Expose
     */
    protected $learningEntity;

    /**
     * @param LearningEntity $learningEntity
     **/
    public function __construct(LearningEntity $learningEntity) {
        $this->learningEntity = $learningEntity;
    }

    public function getLearningEntity() {
        return $this->learningEntity;
    }
    public function getId() {
        return $this->learningEntity->getId();
    }
    public function getName() {
        return $this->learningEntity->getName();
    }
    public function getContent() {
        return $this->learningEntity->getContent();
    }

    public function getContentTwig() {
        $getContentTwigVisitor = new GetContentTwigVisitor();
        return $this->learningEntity->accept($getContentTwigVisitor);
    }
    public function getContentIncludeTwig() {
        $getContentIncludeTwigVisitor = new GetContentIncludeTwigVisitor();
        return $this->learningEntity->accept($getContentIncludeTwigVisitor);
    }

    public function getOutcomes() {
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
}
