<?php

namespace La\LearnodexBundle\Controller;

use JMS\SecurityExtraBundle\Annotation as Security;
use La\CoreBundle\Entity\Answer;
use La\CoreBundle\Entity\LearningEntity;
use La\CoreBundle\Entity\Outcome;
use La\CoreBundle\Entity\Trace;
use La\CoreBundle\Entity\User;
use La\CoreBundle\Model\Outcome\ProcessResultVisitor;
use La\LearnodexBundle\Model\Card;
use La\LearnodexBundle\Model\SimpleRandomCardProvider;
use La\LearnodexBundle\Model\WeightedRandomCardProvider;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Security\Secure(roles="ROLE_USER")
     */
    public function indexAction()
    {
        return $this->render('LaLearnodexBundle:Default:index.html.twig', array(
                'userName'  => $this->getUser()->getUserName(),
        ));
    }

    /**
     * @Security\Secure(roles="ROLE_USER")
     */
    public function cardAction($id = 0)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var $learningEntity LearningEntity */
        if ($id) {
            $learningEntity = $em->getRepository('LaCoreBundle:Action')->find($id);
            $card = new Card($learningEntity);
        } else {
            $cardProvider = $this->get('random_card_provider');
            $card = $cardProvider->getCard();
        }

        if (is_null($card)) {
            return $this->render('LaLearnodexBundle:Card:NoCardsLeft.html.twig', array(
                'userName'  => $this->getUser()->getUserName(),
            ));
        }

        return $this->render('LaLearnodexBundle:Card:Card.html.twig', array(
            'card'      => $card,
            'userName'  => $this->getUser()->getUserName(),
        ));
    }

    /**
     * @Security\Secure(roles="ROLE_USER")
     */
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
