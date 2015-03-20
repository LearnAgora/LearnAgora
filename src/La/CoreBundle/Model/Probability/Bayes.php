<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\CoreBundle\Model\Probability;


class Bayes
{

    private $probabilities = array();
    private $denominator = null;

    public function addProbability($given, $conditional, $initial) {
        $this->probabilities[] = array(
            'given' => $given,
            'conditional' => $conditional,
            'initial' => $initial
        );
    }

    public function getNewProbabilityFor($given) {
        $probability = $this->findProbabilityFor($given);
        return $probability['initial'] * $probability['conditional'] / $this->getDenominator();
    }

    private function findProbabilityFor($given) {
        foreach ($this->probabilities as $probability) {
            if ($probability['given'] == $given) {
                return $probability;
            }
        }
    }

    private function getDenominator() {
        $denominator = 0;
        if (is_null($this->denominator)) {
            foreach ($this->probabilities as $probability) {
                $denominator+= $probability['initial'] * $probability['conditional'];
            }
            $this->denominator = $denominator;
        }
        return $this->denominator;
    }
}
