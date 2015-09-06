<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\CoreBundle\Model\Probability;

use DateTime;
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
            if ($userProbability->getProfile()->getName() == "Fluent") {
                $probability = $userProbability->getProbability();
                $learningEntity = $userProbability->getLearningEntity();
                $eventOn90 = $probability > 0.9;
                $eventOn50 = $probability > 0.5 && !$eventOn90 && is_a($learningEntity,'La\CoreBundle\Entity\Techne') ;
                if ($eventOn50 || $eventOn90) {
                    foreach ($userProbability->getEvents() as $event) {
                        /** @var UserProbabilityEvent $event */
                        $eventOn50 = $eventOn50 && $event->getTreshold() != 50;
                        $eventOn90 = $eventOn90 && $event->getTreshold() != 90;
                    }
                }
                if ($eventOn50 || $eventOn90) {
                    $event = new UserProbabilityEvent();
                    $event->setUserProbability($userProbability);
                    $event->setMessage("well done");
                    if ($eventOn50) {
                        $event->setTreshold(50);
                    } else {
                        $event->setTreshold(90);
                    }
                    $event->setCreatedOn(new DateTime(date('Y-m-d H:i:s', time())));
                    $this->events[] = $event;
                }
            }

        }
        return $this->events;
    }

}
