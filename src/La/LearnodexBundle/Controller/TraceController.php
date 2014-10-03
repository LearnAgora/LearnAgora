<?php

namespace La\LearnodexBundle\Controller;

use La\CoreBundle\Entity\Affinity;
use La\CoreBundle\Entity\ButtonOutcome;
use La\CoreBundle\Entity\LearningEntity;
use La\CoreBundle\Entity\Answer;
use La\CoreBundle\Entity\Outcome;
use La\CoreBundle\Entity\Trace;
use La\CoreBundle\Model\Outcome\ProcessResultVisitor;
use La\LearnodexBundle\Model\Card;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use La\CoreBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class TraceController extends Controller
{
    public function traceAction(Request $request)
    {
        /** @var $user User */
        $user = $this->get('security.context')->getToken()->getUser();

        $answerId = $request->request->get('answer');

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
                $processResultVisitor = new ProcessResultVisitor($user,$em);
                $result->accept($processResultVisitor);
            }
        }

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
                    $processResultVisitor = new ProcessResultVisitor($user,$em);
                    $result->accept($processResultVisitor);
                }
            }
        }

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
                    $processResultVisitor = new ProcessResultVisitor($user,$em);
                    $result->accept($processResultVisitor);
                }
            }
        }

        return $this->redirect($this->generateUrl('card', array('id'=>$id)));

    }
}
