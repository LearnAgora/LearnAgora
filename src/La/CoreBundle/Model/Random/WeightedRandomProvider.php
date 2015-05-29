<?php

namespace La\CoreBundle\Model\Random;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service
 */

class WeightedRandomProvider {
    private $weights = array();
    private $objects = array();

    public function add($object, $weight) {
        $this->objects[] = $object;
        $this->weights[] = $weight;
    }

    public function provide() {
        $randomNumber = rand(0,array_sum($this->weights));

        $index = null;
        $cumulativeWeight = 0;

        foreach ($this->weights as $index => $weight) {
            $cumulativeWeight+= $weight;
            if ($randomNumber <= $cumulativeWeight) {
                break;
            }
        }

        return !is_null($index) ? $this->objects[$index] : null;
    }
} 