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

class CardOutcome {
    private $referenceOutcome;
    private $outcomes;

    /**
     * @param Outcome $referenceOutcome
     **/
    public function __construct(Outcome $referenceOutcome) {
        $this->referenceOutcome = $referenceOutcome;
        $this->outcomes = array();
    }

    /**
     * @return string
     */
    public function getIncludeTwig() {
        $getOutcomeIncludeTwigVisitor = new GetOutcomeIncludeTwigVisitor();
        return $this->referenceOutcome->accept($getOutcomeIncludeTwigVisitor);
    }

    public function addOutcome(Outcome $outcome) {
        $compareOutcomeVisitor = new CompareOutcomeVisitor($outcome);
        if ($this->referenceOutcome->accept($compareOutcomeVisitor)) {
            $this->outcomes[] = $outcome;
        }
    }

    /**
     * @return Outcomes
     */
    public function getOutcomes()
    {
        return $this->outcomes;
    }

    /**
     * @return mixed
     */
    public function getReferenceOutcome()
    {
        return $this->referenceOutcome;
    }

} 