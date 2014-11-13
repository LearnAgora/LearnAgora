<?php

namespace La\LearnodexBundle\Controller;

use La\CoreBundle\Entity\Affinity;
use La\CoreBundle\Entity\ButtonOutcome;
use La\CoreBundle\Entity\LearningEntity;
use La\CoreBundle\Entity\Answer;
use La\CoreBundle\Entity\Outcome;
use La\CoreBundle\Entity\PersonaMatch;
use La\CoreBundle\Entity\Trace;
use La\CoreBundle\Model\ComparePersona;
use La\CoreBundle\Model\Outcome\ProcessResultVisitor;
use La\LearnodexBundle\Model\Card;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use La\CoreBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class TraceController extends Controller
{
    public function traceAction($answerId)
    {
        /** @var $user User */
        $user = $this->get('security.context')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        /** @var $answer Answer */
        $answer = $em->getRepository('LaCoreBundle:Answer')->find($answerId);
        /** @var $outcome Outcome */
        foreach ($answer->getOutcomes() as $outcome) {
            $trace = new Trace();
            $trace->setUser($user);
            $trace->setOutcome($outcome);
            $trace->setCreatedTime(new \DateTime(date('Y-m-d H:i:s',time())));
            $em->persist($trace);
            $em->flush();
            foreach ($outcome->getResults() as $result) {
                $processResultVisitor = $this->get('la_learnodex.process_result_visitor');
                $result->accept($processResultVisitor);
            }
        }

        $this->compareWithPersona($user);

        return $this->redirect($this->generateUrl('card_auto'));
    }

    public function traceButtonAction($id, $caption)
    {
        /** @var $user User */
        $user = $this->get('security.context')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        /** @var $learningEntity LearningEntity */
        $learningEntity = $em->getRepository('LaCoreBundle:LearningEntity')->find($id);

        /** @var $outcome Outcome */
        foreach ($learningEntity->getOutcomes() as $outcome) {
            if (is_a($outcome,'La\CoreBundle\Entity\ButtonOutcome') && $outcome->getCaption() == $caption) {
                $trace = new Trace();
                $trace->setUser($user);
                $trace->setOutcome($outcome);
                $trace->setCreatedTime(new \DateTime(date('Y-m-d H:i:s',time())));
                $em->persist($trace);
                $em->flush();
                foreach ($outcome->getResults() as $result) {
                    $processResultVisitor = $this->get('la_learnodex.process_result_visitor');
                    $result->accept($processResultVisitor);
                }
            }
        }

        $this->compareWithPersona($user);

        return $this->redirect($this->generateUrl('card_auto'));
    }

    public function traceUrlAction($id)
    {
        /** @var $user User */
        $user = $this->get('security.context')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        /** @var $learningEntity LearningEntity */
        $learningEntity = $em->getRepository('LaCoreBundle:LearningEntity')->find($id);

        /** @var $outcome Outcome */
        foreach ($learningEntity->getOutcomes() as $outcome) {
            if (is_a($outcome,'La\CoreBundle\Entity\UrlOutcome')) {
                $trace = new Trace();
                $trace->setUser($user);
                $trace->setOutcome($outcome);
                $trace->setCreatedTime(new \DateTime(date('Y-m-d H:i:s',time())));
                $em->persist($trace);
                $em->flush();
                foreach ($outcome->getResults() as $result) {
                    $processResultVisitor = $this->get('la_learnodex.process_result_visitor');
                    $result->accept($processResultVisitor);
                }
            }
        }

        $this->compareWithPersona($user);

        return $this->redirect($this->generateUrl('card', array('id'=>$id)));
    }

    private function compareWithPersona($user)
    {
        $em = $this->getDoctrine()->getManager();
        $personalities = $em->getRepository('LaCoreBundle:Persona')->findAll();

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

    public function removeMyTracesAction()
    {
        /** @var $user User */
        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        foreach ($user->getPersonas() as $persona) {
            $em->remove($persona);
        }
        foreach ($user->getAffinities() as $affinity) {
            $em->remove($affinity);
        }
        foreach ($user->getTraces() as $trace) {
            $em->remove($trace);
        }
        $em->flush();

        return $this->redirect($this->generateUrl('homepage'));
    }
}
