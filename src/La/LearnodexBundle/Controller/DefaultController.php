<?php

namespace La\LearnodexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use La\CoreBundle\Entity\User;

class DefaultController extends Controller
{
    public function indexAction()
    {
        /** @var $user User */
        $user = $this->get('security.context')->getToken()->getUser();

        return $this
            ->render('LaLearnodexBundle:Default:index.html.twig', array(
            'userName'   => $user->getUserName(),
        ));
    }

    public function cardAction($id = 0)
    {
        /** @var $user User */
        $user = $this->get('security.context')->getToken()->getUser();

        $id = 9;
        $em = $this->getDoctrine()->getManager();
        /** @var $learningEntity LearningEntity */
        $learningEntity = $em->getRepository('LaCoreBundle:Action')->find($id);

        return $this->render('LaLearnodexBundle:Default:card.html.twig', array(
            'userName'       => $user->getUserName(),
            'learningEntity' => $learningEntity,
        ));

    }
}
