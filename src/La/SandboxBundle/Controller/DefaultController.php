<?php

namespace La\SandboxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('LaSandboxBundle:Default:index.html.twig', array('name' => $name));
    }
}
