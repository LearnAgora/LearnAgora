<?php

namespace La\AngularBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function cardAction()
    {
        return $this->render('LaAngularBundle:Default:card.html.twig');
    }
}
