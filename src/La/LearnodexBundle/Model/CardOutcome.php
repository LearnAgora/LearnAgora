<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/10/14
 * Time: 11:51 PM
 */

namespace La\LearnodexBundle\Model;


use La\CoreBundle\Entity\Outcome;
use La\LearnodexBundle\Model\Visitor\GetOutcomeIncludeTwigVisitor;

class CardOutcome {
    private $outcome = null;

    /**
     * @param Outcome $outcome
     **/
    public function __construct(Outcome $outcome) {
        $this->outcome = $outcome;
    }

    public function getIncludeTwig() {
        $getOutcomeIncludeTwigVisitor = new GetOutcomeIncludeTwigVisitor();
        return $this->outcome->accept($getOutcomeIncludeTwigVisitor);
    }

    /**
     * @return Outcome $outcome
     */
    public function getOutcome()
    {
        return $this->outcome;
    }

} 