<?php

namespace La\CoreBundle\Controller;

use La\CoreBundle\Entity\User;
use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\Action;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class AgoraController extends Controller
{
    public function indexAction(Request $request, $id=0)
    {
        /** @var $user User */
        $user = $this->get('security.context')->getToken()->getUser();
        $agora = new Agora();
        if ($id) {
            $em = $this->getDoctrine()->getManager();
            $agora = $em->getRepository('LaCoreBundle:Agora')->find($id);

            if (!$agora) {
                throw $this->createNotFoundException(
                    'No Agora found for id ' . $id
                );
            }
        }

        $form = $this->createFormBuilder($agora)
            ->setAction($this->generateUrl('la_core_agora',array('id'=>$id)))
            ->add('name','text', array('label' => 'Name'))
            ->add('description','text', array('label' => 'Description'))
            ->add('create','submit', array(
                'label' => ($id ? 'Save' : 'Create') . ' Agora'
            ))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($agora);
            $em->flush();

            return $this->redirect($this->generateUrl('la_core_agora_add_action', array('agoraId'=>$agora->getId())));
        }

        return $this->render('LaCoreBundle:Particle:new.html.twig',array(
            'form'     =>$form->createView(),
            'userName' => $user->getUserName(),
        ));
    }

    public function addActionAction(Request $request, $agoraId) {
        /** @var $user User */
        $user = $this->get('security.context')->getToken()->getUser();
        $action = new Action();
        if ($agoraId) {
            $em = $this->getDoctrine()->getManager();
            $agora = $em->getRepository('LaCoreBundle:Agora')->find($agoraId);

            if (!$agora) {
                throw $this->createNotFoundException(
                    'No Agora found for id ' . $agoraId
                );
            }
        }

        $form = $this->createFormBuilder($action)
            ->setAction($this->generateUrl('la_core_agora_add_action',array('agoraId'=>$agoraId)))
            ->add('name','text', array('label' => 'Name'))
            ->add('description','text', array('label' => 'Description'))
            ->add('create','submit', array(
                'label' => 'Create Action'
            ))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($action);
            $em->flush();

            return $this->redirect($this->generateUrl('la_core_agora_links', array('agoraId'=>$agoraId)));
        }

        return $this->render('LaCoreBundle:Particle:new.html.twig',array(
            'form'     =>$form->createView(),
            'userName' => $user->getUserName(),
        ));
    }

    public function linksAction(Request $request, $agoraId) {
        die("will manage links now");
    }
}
