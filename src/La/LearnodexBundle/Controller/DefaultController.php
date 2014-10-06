<?php

namespace La\LearnodexBundle\Controller;

use La\CoreBundle\Entity\Affinity;
use La\CoreBundle\Entity\LearningEntity;
use La\CoreBundle\Entity\Answer;
use La\CoreBundle\Entity\Outcome;
use La\CoreBundle\Entity\Trace;
use La\CoreBundle\Model\Outcome\ProcessResultVisitor;
use La\LearnodexBundle\Model\Card;
use La\LearnodexBundle\Model\NaiveRandomCardProvider;
use La\LearnodexBundle\Model\WeightedRandomCardProvider;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use La\CoreBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LaLearnodexBundle:Default:index.html.twig', array(
                'userName'  => $this->getUser()->getUserName(),
        ));
    }

    public function cardAction($id = 0)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var $learningEntity LearningEntity */
        if ($id) {
            $learningEntity = $em->getRepository('LaCoreBundle:Action')->find($id);
            $card = new Card($learningEntity);
        } else {
            //$cardProvider = new NaiveRandomCardProvider($em->getRepository('LaCoreBundle:Action'));
            $cardProvider = new WeightedRandomCardProvider($this->getUser(),$em->getRepository('LaCoreBundle:Action'));
            $card = $cardProvider->getCard();
        }


        return $this->render('LaLearnodexBundle:Card:Card.html.twig', array(
            'card'      => $card,
            'userName'  => $this->getUser()->getUserName(),
        ));
    }

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
            $em->persist($trace);
            $em->flush();
            foreach ($outcome->getResults() as $result) {
                $processResultVisitor = new ProcessResultVisitor($user,$em);
                $result->accept($processResultVisitor);
            }
        }

        return $this->redirect($this->generateUrl('card_auto'));
    }
}
