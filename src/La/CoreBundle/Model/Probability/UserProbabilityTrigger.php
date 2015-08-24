<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\CoreBundle\Model\Probability;

use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\UserProbability;
use La\CoreBundle\Entity\UserProbabilityEvent;


/**
 * @DI\Service
 */
class UserProbabilityTrigger
{
    private $events = array();


    public function getEvents($userProbabilities) {
        $this->events = array();
        foreach ($userProbabilities as $userProbability) {
            /** @var UserProbability $userProbability */
            if ($userProbability->getProbability()>0.9) {
                if (count($userProbability->getEvents()) == 0) {
                    $event = new UserProbabilityEvent();
                    $event->setUserProbability($userProbability);
                    $event->setMessage("well done");
                    $this->events[] = $event;
                }
            }
        }
        return $this->events;
    }

}
