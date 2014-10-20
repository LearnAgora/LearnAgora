<?php

namespace La\LearnodexBundle\Model;

use La\CoreBundle\Entity\Affinity;
use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\PersonaMatch;
use La\CoreBundle\Entity\User;
use La\CoreBundle\Model\ComparePersona;

class UpdateAllAffinities
{
    protected $em;

    public function __construct($em) {
        $this->em = $em;

        $agoraList = $em->getRepository('LaCoreBundle:Agora')->findAll();
        $userList = $em->getRepository('LaCoreBundle:User')->findAll();
        $personalities = $em->getRepository('LaCoreBundle:Persona')->findAll();



        /** @var $agora Agora */
        foreach ($agoraList as $agora) {
            /** @var $user User */
            foreach ($userList as $user) {
                if ($user->isEnabled()) {
                    $downLinks = $agora->getDownlinks();
                    $affinityForOutcome = 0;
                    $totalWeight = 0;
                    /** @var $downLink Uplink */
                    foreach ($downLinks as $downLink) {
                        $child = $downLink->getChild();
                        $outcomes = $child->getOutcomes();
                        $weight = $child->getContent()->getDuration() * max($downLink->getWeight(),1);
                        $lastResult = 0;
                        $lastTimestamp = 0;
                        /** @var $outcome Outcome */
                        foreach ($outcomes as $outcome) {
                            $results = $outcome->getResults();
                            /** @var $result Result */
                            foreach ($results as $result) {
                                if (is_a($result,'La\CoreBundle\Entity\AffinityResult')) {
                                    $traces = $outcome->getTraces();
                                    /** @var $trace Trace */
                                    foreach ($traces as $trace) {
                                        if ($trace->getUser()->getId() == $user->getId()) {
                                            $timestamp = strtotime($trace->getCreatedTime()->format('Y-m-d H:i:s'));
                                            if ($timestamp > $lastTimestamp) {
                                                $lastTimestamp = $timestamp;
                                                $lastResult = $result->getValue();
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $affinityForOutcome+= $weight*$lastResult;
                        $totalWeight+= $weight*100;
                    }

                    $affinityValue = $totalWeight ? 100*$affinityForOutcome/$totalWeight : 0;
                    $affinityValue = $affinityValue<0 ? 0 : $affinityValue;

                    $affinity = $this->em->getRepository('LaCoreBundle:Affinity')->findOneBy(
                        array(
                            'user' => $user,
                            'agora' => $agora
                        )
                    );
                    if (!$affinity) {
                        $affinity = new Affinity();
                        $affinity->setUser($user);
                        $affinity->setAgora($agora);
                    }
                    $affinity->setValue($affinityValue);
                    $this->em->persist($affinity);
                    $this->em->flush();


                    $comparePersona = new ComparePersona();
                    foreach ($personalities as $personality) {
                        $difference = $comparePersona->compare($user,$personality->getUser());
                        $personaMatch = $em->getRepository('LaCoreBundle:PersonaMatch')->findOneBy(
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
                        $em->persist($personaMatch);
                    }
                    $em->flush();
                }
            }
        }
    }

    private function compareWithPersona($user) {
    }
}
