<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\CoreBundle\Model\Probability;


use La\CoreBundle\Entity\UserProbability;

class BayesForUserProbabilities
{

    private $bayes = null;
    private $userProbabilities = array();

    public function __construct() {
        $this->bayes = new Bayes();
    }

    public function add(UserProbability $userProbability, $conditionalProbability) {
        $index = $userProbability->getProfile()->getId();
        $this->bayes->addProbability($index,$userProbability->getProbability(),$conditionalProbability);
        $this->userProbabilities[$index] = $userProbability;
    }

    public function updateProbabilities() {
        foreach ($this->userProbabilities as $index => $userProbability) {
            /* @var UserProbability $userProbability */
            $userProbability->setProbability($this->bayes->getNewProbabilityFor($index));
        }
    }

    public function getProbabilities() {
        return $this->userProbabilities;
    }

}
