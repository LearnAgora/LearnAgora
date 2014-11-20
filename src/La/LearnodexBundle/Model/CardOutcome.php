<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/10/14
 * Time: 11:51 PM
 */

namespace La\LearnodexBundle\Model;

use Doctrine\Common\Collections\Collection;
use La\CoreBundle\Entity\Outcome;
use La\LearnodexBundle\Model\Visitor\CompareOutcomeVisitor;
use La\LearnodexBundle\Model\Visitor\GetOutcomeIncludeTwigVisitor;

class CardOutcome
{
    private $referenceOutcome;
    private $outcome;
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
        $this->outcome = null;
    }

    /**
     * @return string
     */
    public function getIncludeTwig()
    {
        $getOutcomeIncludeTwigVisitor = new GetOutcomeIncludeTwigVisitor();
        return $this->referenceOutcome->accept($getOutcomeIncludeTwigVisitor);
    }

    public function setOutcomeFromCollection(Collection $outcomes)
    {
        foreach ($outcomes as $outcome) {
            if (get_class($outcome) == get_class($this->referenceOutcome)) {
                $compareOutcomeVisitor = new CompareOutcomeVisitor($outcome);
                if ($this->referenceOutcome->accept($compareOutcomeVisitor)) {
                    $this->outcome = $outcome;
                }
            }
        }
    }
    public function getNumberOfStars()
    {
        return isset($this->outcome) ? $this->getStarsForValue($this->outcome->getAffinity()) : 0;
    }
    public function getValueForStars($stars)
    {
        return isset($this->affinityForStars[$stars]) ? $this->affinityForStars[$stars] : 0;
    }
    public function getStarsForValue($value)
    {
        $numberOfStars = 0;
        foreach ($this->affinityForStars as $stars => $affinity) {
            if ($value >= $affinity) {
                $numberOfStars = $stars;
            }
        }
        return $numberOfStars;
    }

    /**
     * @return Outcome
     */
    public function getOutcome()
    {
        return $this->outcome;
    }

    /**
     * @return mixed
     */
    public function getReferenceOutcome()
    {
        return $this->referenceOutcome;
    }
}
