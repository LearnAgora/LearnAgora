<?php

namespace La\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class ParticleController extends Controller
{
    public function indexAction($type, $id=0, $request=null)
    {
        /*I AM BLOCKED HERE

            i tried to have one controller for creating all 3 types of particles but i can make the $request work
            maybe i'm making it too difficult, i will try again with an explicit AgoraController
        */


        $user = $this->get('security.context')->getToken()->getUser();

        //check type
        if (!in_array($type,array("Agora","Objective","Action"))) {
            throw $this->createNotFoundException(
                'Invalid particle type '.$type
            );
        }

        if ($id>0) {
            $em = $this->getDoctrine()->getManager();
            $particle = $em->getRepository('LaCoreBundle:'.$type)->find($id);

            if (!$particle) {
                throw $this->createNotFoundException(
                    'No ' . $type . ' found for id ' . $id
                );
            }
        } else {
            $className = "La\\CoreBundle\\Entity\\" . $type;
            if (class_exists($className)) {
                $particle = new $className;
            } else {
                throw $this->createNotFoundException(
                    'Class ' . $className . ' not found'
                );
            }

        }

        $form = $this->createFormBuilder($particle)
            ->setAction($this->generateUrl('la_core_new_particle',array('type'=>$type)))
            ->add('name','text', array('label' => 'Name'))
            ->add('description','text', array('label' => 'Description'))
            ->add('create','submit', array(
                'label' => ($particle->getId() == 0 ? 'Create ' : 'Save ') . $type
            ))
            ->getForm();

        if (!is_null($request)) {
            $form->handleRequest($request);
        };
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($particle);
            $em->flush();

            //return $this->redirect($this->generateUrl('login'));
        }

        return $this->render('LaCoreBundle:Particle:new.html.twig',array(
            'type'     => $type,
            'form'=>$form->createView(),
            'userName' => $user->getUserName(),
        ));
    }

    public function index2Action($type,$id=0)
    {
        $user = $this->get('security.context')->getToken()->getUser();

        //check type
        if (!in_array($type,array("Agora","Objective","Action"))) {
            throw $this->createNotFoundException(
                'Invalid particle type '.$type
            );
        }

        if ($id>0) {
            $em = $this->getDoctrine()->getManager();
            $particle = $em->getRepository('LaCoreBundle:'.$type)->find($id);

            if (!$particle) {
                throw $this->createNotFoundException(
                    'No ' . $type . ' found for id ' . $id
                );
            }
        } else {
            $className = "La\\CoreBundle\\Entity\\" . $type;
            $particle = new $className;
            $particle->setName('');
            $particle->setDescription('');
        }

        return $this->render('LaCoreBundle:Particle:new.backup.html.twig', array(
            'type'     => $type,
            'particle' => $particle,
            'userName' => $user->getUserName()
        ));
    }

    public function saveAction(Request $request) {
        //quick and dirty
        $content = $this->get("request")->getContent();
        $data = array();
        if (!empty($content))
        {
            $data = json_decode($content, true); // 2nd param to get as array
        }

        $id = $data['id'];
        $name = $data['name'];
        $description = isset($data['description']) ? $data['description'] : "";
        $type = $data['type'];

        //check type
        if (!in_array($type,array("Agora","Objective","Action"))) {
            die(json_encode(array('particleId' => 'error (id='.$id.'),type='.$type)));
        }

        if ($id>0) {
            $em = $this->getDoctrine()->getManager();
            $particle = $em->getRepository('LaCoreBundle:'.$type)->find($id);

            if (!$particle) {
                throw $this->createNotFoundException(
                    'No ' . $type . ' found for id ' . $id
                );
            }
        } else {
            $className = "La\\CoreBundle\\Entity\\" . $type;
            $particle = new $className;
        }
        $particle->setName($name);
        $particle->setDescription($description);

        $em = $this->getDoctrine()->getManager();
        $em->persist($particle);
        $em->flush();

        header('Content-Type: application/json');
        echo json_encode(array('particleId' => $particle->getId()));
        die();
    }

}
