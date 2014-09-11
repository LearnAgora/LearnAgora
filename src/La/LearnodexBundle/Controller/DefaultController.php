<?php

namespace La\LearnodexBundle\Controller;

use La\CoreBundle\Entity\LearningEntity;
use La\CoreBundle\Entity\Trace;
use La\LearnodexBundle\Model\Card;
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
        } else {
            $learningEntities = $em->getRepository('LaCoreBundle:Action')->findAll();
            shuffle($learningEntities);
            $learningEntity = $learningEntities[0];
        }

        $card = new Card($learningEntity);
        return $this->render('LaLearnodexBundle:Card:Card.html.twig', array(
            'card'      => $card,
            'userName'  => $this->getUser()->getUserName(),
        ));
    }

    public function traceAction(Request $request)
    {
        /** @var $user User */
        $user = $this->get('security.context')->getToken()->getUser();
        $answers = $request->request->get('answers');

        $em = $this->getDoctrine()->getManager();
        foreach ($answers as $answerId) {
            $answer = $em->getRepository('LaCoreBundle:Answer')->find($answerId);
            foreach ($answer->getOutcomes() as $outcome) {
                $trace = new Trace();
                $trace->setUser($user);
                $trace->setOutcome($outcome);
                $em->persist($trace);
            }
        }
        $em->flush();

        return $this->redirect($this->generateUrl('card_auto'));
    }
}
