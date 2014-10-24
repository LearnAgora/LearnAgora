<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/10/14
 * Time: 11:51 PM
 */

namespace La\LearnodexBundle\Model;

use La\CoreBundle\Entity\Outcome;
use La\LearnodexBundle\Model\Visitor\CompareOutcomeVisitor;
use La\LearnodexBundle\Model\Visitor\GetOutcomeIncludeTwigVisitor;

class CardOutcome
{
    private $referenceOutcome;
    private $outcomes;
    public $affinityForStars = array(
        '0' => 0,
        '1' => 20,
        '2' => 40,
        '3' => 60,
        '4' => 80,
        '5' => 100,
    );

    /**
     * @param Outcome $referenceOutcome
     **/
    public function __construct(Outcome $referenceOutcome)
    {
        $this->referenceOutcome = $referenceOutcome;
        $this->outcomes = array();
    }

    /**
     * @return string
     */
    public function getIncludeTwig()
    {
        $getOutcomeIncludeTwigVisitor = new GetOutcomeIncludeTwigVisitor();
        return $this->referenceOutcome->accept($getOutcomeIncludeTwigVisitor);
    }

    public function addOutcome(Outcome $outcome)
    {
        if (get_class($outcome) == get_class($this->referenceOutcome)) {
            $compareOutcomeVisitor = new CompareOutcomeVisitor($outcome);
            if ($this->referenceOutcome->accept($compareOutcomeVisitor)) {
                $this->outcomes[] = $outcome;
            }
        }
    }
    public function getNumberOfStars()
    {
        $numberOfStars = 0;
        if (isset($this->outcomes[0])) {
            foreach ($this->outcomes[0]->getResults() as $result) {
                $value = $result->getValue();
                foreach ($this->affinityForStars as $stars => $affinity) {
                    if ($value >= $affinity) {
                        $numberOfStars = $stars;
                    }
                }
            }
        }
        return $numberOfStars;
    }
    public function getValueForStars($stars)
    {
        return isset($this->affinityForStars[$stars]) ? $this->affinityForStars[$stars] : 0;
    }

    /**
     * @return Outcomes
     */
    public function getOutcomes()
    {
        return $this->outcomes;
    }
    /**
     * @return Outcome
     */
    public function getOutcome()
    {
        return $this->outcomes[0];
    }

    /**
     * @return mixed
     */
    public function getReferenceOutcome()
    {
        return $this->referenceOutcome;
    }
}
