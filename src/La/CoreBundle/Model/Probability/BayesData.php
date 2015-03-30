<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\CoreBundle\Model\Probability;


use La\CoreBundle\Entity\UserProbability;

class BayesData
{
    const PROFILE_ID = "profileId";
    const USER_PROBABILITY = "userProbability";
    const OUTCOME_PROBABILITY_VALUE = "outcomeProbability";

    private $data = array();
    private $profilesWithNullUserProbability = array();
    private $profilesWithNullOutcomeProbability = array();
    private $hasNullUserProbability = false;
    private $hasNullOutcomeProbability = false;


    public function __construct(array $records) {
        foreach ($records as $result) {
            $this->add($result);
        }
    }

    static public function newInstance(array $records) {
        return new self($records);
    }

    /**
     * @return array
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @return int
     */
    public function getNumProfilesWithUserProbability() {
        return count($this->data) - count($this->profilesWithNullUserProbability);
    }

    /**
     * @param $record
     */
    private function add($record) {

        if (is_null($record[self::USER_PROBABILITY])) {
            $this->hasNullUserProbability = true;
            $this->profilesWithNullUserProbability[] = $record[self::PROFILE_ID];
        }

        if (is_null($record[self::OUTCOME_PROBABILITY_VALUE])) {
            $this->hasNullOutcomeProbability = true;
            $this->profilesWithNullOutcomeProbability[] = $record[self::PROFILE_ID];
        }
        $this->data[$record[self::PROFILE_ID]] = $record;
    }

    /**
     * @return bool
     */
    public function hasNullUserProbability() {
        return $this->hasNullUserProbability;
    }

    /**
     * @return array
     */
    public function getProfilesWithNullUserProbability() {
        return $this->profilesWithNullUserProbability;
    }

    /**
     * @return array
     */
    public function getProfilesWithMissingOutcomeProbability() {
        return $this->profilesWithNullOutcomeProbability;
    }

    /**
     * @param $index
     * @return UserProbability
     */
    public function getUserProbability($index) {
        return $this->data[$index][self::USER_PROBABILITY];
    }

    /**
     * @return UserProbability
     */
    public function getTopUserProbability() {
        $topValue = 0;
        /* @var UserProbability $topUserProbability */
        $topUserProbability = null;
        foreach ($this->data as $record) {
            /* @var UserProbability $userProbability */
            $userProbability = $record[self::USER_PROBABILITY];
            if ($userProbability->getProbability() > $topValue) {
                $topValue = $userProbability->getProbability();
                $topUserProbability = $userProbability;
            }
        }
        return $topUserProbability;
    }
    /**
     * @return array
     */
    public function getUserProbabilities()  {
        $userProbabilities = array();
        foreach ($this->data as $record) {
            $userProbabilities[] = $record[self::USER_PROBABILITY];
        }
        return $userProbabilities;
    }

    /*
    public function setUserProbability($profileId, UserProbability $userProbability) {
        $this->data[$profileId][self::USER_PROBABILITY] = $userProbability;
    }

    public function setOutcomeProbability($profileId, $outcomeProbabilityValue) {
        $this->data[$profileId][self::OUTCOME_PROBABILITY_VALUE] = $outcomeProbabilityValue;
    }
    */

    public function normalizeUserProbabilities() {
        $totalProbability = 0;
        $data = $this->data;

        foreach ($data as $record) {
            /* @var UserProbability $userProbability */
            $userProbability = $record[self::USER_PROBABILITY];
            $totalProbability+= $userProbability->getProbability();
        }
        foreach ($data as $record) {
            /* @var UserProbability $userProbability */
            $userProbability = $record[self::USER_PROBABILITY];
            $userProbability->setProbability($userProbability->getProbability() / $totalProbability);
        }

        return new self($data);
    }

    public function process() {
        $bayes = new Bayes();
        foreach ($this->data as $record) {
            /* @var UserProbability $userProbability */
            $userProbability = $record[self::USER_PROBABILITY];
            $index = $userProbability->getProfile()->getId();
            $bayes->addProbability($index,$userProbability->getProbability(),$record[self::OUTCOME_PROBABILITY_VALUE]);
        }

        foreach ($this->data as $record) {
            /* @var UserProbability $userProbability */
            $userProbability = $record[self::USER_PROBABILITY];
            $index = $userProbability->getProfile()->getId();
            $userProbability->setProbability($bayes->getNewProbabilityFor($index));
        }
    }

    /**
     * @return bool
     */
    public function hasNullOutcomeProbability() {
        return $this->hasNullOutcomeProbability;
    }
    public function updateOutcomeProbabilities() {

    }
}
