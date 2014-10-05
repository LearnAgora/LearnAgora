<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\CoreBundle\Model;


use La\CoreBundle\Entity\PersonaMatch;

class ComparePersona
{
    protected $em = null;

    public function __construct($em) {
        $this->em = $em;
    }

    public function compareAll($user) {
        $userAffinities = $user->getAffinities();
        $sortedUserAffinities = array();
        foreach ($userAffinities as $userAffinity) {
            $sortedUserAffinities[$userAffinity->getAgora()->getId()] = $userAffinity;
        }

        $persona = $this->em->getRepository('LaCoreBundle:Persona')->findAll();
        foreach ($persona as $person) {
            $personaAffinities = $person->getUser()->getAffinities();
            $difference = 0;

            foreach ($personaAffinities as $personaAffinity) {
                $agoraId = $personaAffinity->getAgora()->getId();
                $userAffinityValue = isset($sortedUserAffinities[$agoraId]) ? $sortedUserAffinities[$agoraId]->getValue() : 0;
                $difference += abs($personaAffinity->getValue() - $userAffinityValue);
            }

            $personaMatch = $this->em->getRepository('LaCoreBundle:PersonaMatch')->findOneBy(
                array(
                    'user' => $user,
                    'persona' => $person
                )
            );
            if (!$personaMatch) {
                $personaMatch = new PersonaMatch();
                $personaMatch->setUser($user);
                $personaMatch->setPersona($person);
            }
            $personaMatch->setDifference($difference);
            $this->em->persist($personaMatch);
            echo "Difference in affinity for " . $person->getUser()->getUserName() . " is " . $difference . "<br />";
        }
        $this->em->flush();
    }
}