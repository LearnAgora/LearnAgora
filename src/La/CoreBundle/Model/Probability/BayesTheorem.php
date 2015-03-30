<?php

namespace La\CoreBundle\Model\Probability;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service
 */
class BayesTheorem
{
    /**
     * @param UserProbabilityCollection $userProbabilityCollection
     * @param OutcomeProbabilityCollection $outcomeProbabilityCollection
     */
    public function applyTo(UserProbabilityCollection $userProbabilityCollection, OutcomeProbabilityCollection $outcomeProbabilityCollection)
    {
        $profiles = $userProbabilityCollection->getProfiles();

        $denominator = 0;
        foreach ($profiles as $profile) {
            $userProbability = $userProbabilityCollection->getUserProbabilityForProfile($profile);
            $outcomeProbability = $outcomeProbabilityCollection->getOutcomeProbabilityForProfile($profile);
            $denominator+= $userProbability->getProbability() * $outcomeProbability->getProbability();
        }

        foreach ($profiles as $profile) {
            $userProbability = $userProbabilityCollection->getUserProbabilityForProfile($profile);
            $outcomeProbability = $outcomeProbabilityCollection->getOutcomeProbabilityForProfile($profile);

            $newProbabilityValue = $userProbability->getProbability() * $outcomeProbability->getProbability() / $denominator;

            $userProbability->setProbability($newProbabilityValue);
            $userProbabilityCollection->setUserProbabilityForProfile($profile,$userProbability);
        }

    }
}
