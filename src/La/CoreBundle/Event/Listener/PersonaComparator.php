<?php

namespace La\CoreBundle\Event\Listener;

use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Event\TraceEvent;
use La\CoreBundle\Events;

/**
 * @DI\Service
 */
class PersonaComparator
{
    /**
     * @DI\Observe(Events::TRACE_CREATED)
     *
     * @param TraceEvent $traceEvent
     */
    public function onResult(TraceEvent $traceEvent)
    {
        //        $this->compareWithPersona($user);
    }

    /*
    private function compareWithPersona($user)
    {
        $personalities = $this->personaRepository->findAll();

        $comparePersona = new ComparePersona();

        foreach ($personalities as $personality) {
            $difference = $comparePersona->compare($user,$personality->getUser());
            $personaMatch = $this->personaMatchRepository->findOneBy(
                array(
                    'user' => $user,
                    'persona' => $personality
                )
            );
            if (!$personaMatch) {
                $personaMatch = new PersonaMatch();
                $personaMatch->setUser($user);
                $personaMatch->setPersona($personality);
            }
            $personaMatch->setDifference($difference);
            $this->entityManager->persist($personaMatch);
        }
        $this->entityManager->flush();
    }
    */

}
