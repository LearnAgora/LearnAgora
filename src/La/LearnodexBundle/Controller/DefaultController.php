<?php

namespace La\LearnodexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();

        return $this->render('LaLearnodexBundle:Default:index.html.twig', array('userName' => $user->getUserName()));
    }
}
