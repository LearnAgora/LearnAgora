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
use Symfony\Bridge\Monolog\Logger;


/**
 * @DI\Service
 */
class UserProbabilityTrigger
{
    /**
     * @var Logger
     */
    private $logger;

    private $events = array();

    /**
     * Constructor.
     *
     * @param Logger $logger
     *
     * @DI\InjectParams({
     *  "logger" =  @DI\Inject("monolog.logger.event")
     * })
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function getEvents($userProbabilities) {
        $this->events = array();
        foreach ($userProbabilities as $userProbability) {
            /** @var UserProbability $userProbability */
            if ($userProbability->getProfile()->getName() == "Fluent") {
                $probability = $userProbability->getProbability();
                $learningEntity = $userProbability->getLearningEntity();
                $this->logger->addDebug("BARTDEBUG: check notification for ".$learningEntity->getName()." with a probability for Fluent=".$probability);
                $eventOn90 = $probability > 0.9;
                $eventOn50 = $probability > 0.5 && !$eventOn90 && is_a($learningEntity,'La\CoreBundle\Entity\Techne') ;
                $this->logger->addDebug("BARTDEBUG: the current state is: ");
                $this->logger->addDebug($eventOn50 ? "BARTDEBUG: event on 50" : "BARTDEBUG: no event on 50");
                $this->logger->addDebug($eventOn90 ? "BARTDEBUG: event on 90" : "BARTDEBUG: no event on 90");
                if ($eventOn50 || $eventOn90) {
                    foreach ($userProbability->getEvents() as $event) {
                        /** @var UserProbabilityEvent $event */
                        $this->logger->addDebug("BARTDEBUG: check event with id ". $event->getId());
                        $eventOn50 = $eventOn50 && $event->getThreshold() != 50;
                        $eventOn90 = $eventOn90 && $event->getThreshold() != 90;
                    }
                }
                $this->logger->addDebug("BARTDEBUG: the current state is: ");
                $this->logger->addDebug($eventOn50 ? "BARTDEBUG: event on 50" : "BARTDEBUG: no event on 50");
                $this->logger->addDebug($eventOn90 ? "BARTDEBUG: event on 90" : "BARTDEBUG: no event on 90");
                if ($eventOn50 || $eventOn90) {
                    $this->logger->addDebug("BARTDEBUG: we will create a new event");
                    $event = new UserProbabilityEvent();
                    $event->setUserProbability($userProbability);
                    $event->setMessage("well done");
                    if ($eventOn50) {
                        $event->setThreshold(50);
                    } else {
                        $event->setThreshold(90);
                    }
                    $event->setCreatedOn(new DateTime(date('Y-m-d H:i:s', time())));
                    $userProbability->addEvent($event);
                    $this->events[] = $event;
                }
            }

        }
        $this->logger->addDebug("BARTDEBUG: we have now ".count($this->events)." events");
        return $this->events;
    }

}
