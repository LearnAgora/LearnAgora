<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\CoreBundle\Model\Trace;

use DateTime;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\User;
use La\CoreBundle\Entity\UserTraceEvent;


/**
 * @DI\Service
 */
class UserTraceTrigger
{

    private $events = array();

    public function getEvents(User $user) {
        $threshold = 30;
        $this->events = array();
        $traces = $user->getTraces();
        if (count($traces) == $threshold) {
            $event = new UserTraceEvent();
            $event->setUser($user);
            $event->setMessage("well done");
            $event->setThreshold($threshold);
            $event->setCreatedOn(new DateTime(date('Y-m-d H:i:s', time())));
            $this->events[] = $event;
        }
        return $this->events;
    }

}
