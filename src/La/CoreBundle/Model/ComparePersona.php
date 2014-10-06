<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\CoreBundle\Model;


use La\CoreBundle\Entity\User;

class ComparePersona
{
    /**
     * @param User $user1
     * @param User $user2
     * @return float
     */
    public function compare($user1,$user2)
    {
        $user1Affinities = $user1->getAffinities();
        $sortedUser1Affinities = array();
        foreach ($user1Affinities as $userAffinity) {
            $sortedUser1Affinities[$userAffinity->getAgora()->getId()] = $userAffinity;
        }

        $user2Affinities = $user2->getAffinities();
        $differenceWithUser2 = 0;

        $numAffinities = count($user2Affinities);
        if ($numAffinities == 0) {
            return 100;
        }

        foreach ($user2Affinities as $user2Affinity) {
            $agoraId = $user2Affinity->getAgora()->getId();
            $userAffinityValue = isset($sortedUser1Affinities[$agoraId]) ? $sortedUser1Affinities[$agoraId]->getValue() : 0;
            $difference = abs($user2Affinity->getValue() - $userAffinityValue);
            $difference = min(100,$difference);
            $differenceWithUser2 += $difference;
        }

        $differenceWithUser2 /= $numAffinities;

        return $differenceWithUser2;
    }
}