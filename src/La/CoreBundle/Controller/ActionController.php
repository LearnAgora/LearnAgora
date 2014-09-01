<?php

namespace La\CoreBundle\Controller;

use La\CoreBundle\Entity\User;
use La\CoreBundle\Entity\Action;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class ActionController extends Controller
{
    public function indexAction(Request $request, $id=0)
    {
        /** @var $user User */
        $user = $this->get('security.context')->getToken()->getUser();
        $action = new Action();
        if ($id) {
            $em = $this->getDoctrine()->getManager();
            $action = $em->getRepository('LaCoreBundle:Action')->find($id);

            if (!$action) {
                throw $this->createNotFoundException(
                    'No Action found for id ' . $id
                );
            }
        }

        $form = $this->createFormBuilder($action)
            //->setAction($this->generateUrl('la_core_agora',array('id'=>$id)))
            ->setAction($this->generateUrl('la_core_action',array('id'=>$id)))
            ->add('name','text', array('label' => 'Name'))
            ->add('description','text', array('label' => 'Description'))
            ->add('create','submit', array(
                'label' => ($id ? 'Save' : 'Create') . ' Action'
            ))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($action);
            $em->flush();

            //return $this->redirect($this->generateUrl('login'));
        }

        return $this->render('LaCoreBundle:Particle:new.html.twig',array(
            'form'     =>$form->createView(),
            'userName' => $user->getUserName(),
        ));
    }

}
