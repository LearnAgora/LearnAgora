<?php

namespace La\LearnodexBundle\Controller;

use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\Persona;
use La\CoreBundle\Entity\User;
use La\LearnodexBundle\Forms\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PersonaController extends Controller
{
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $persona = $em->getRepository('LaCoreBundle:Persona')->findAll();

        return $this->render('LaLearnodexBundle:Persona:index.html.twig',array(
            'persona'      => $persona,
        ));
    }

    public function editAction(Request $request, $id=0)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var $persona Persona */
        if ($id) {
            $persona = $em->getRepository('LaCoreBundle:Persona')->find($id);
        } else {
            $persona = new Persona();
        }

        $form = $this->createFormBuilder($persona)
            ->setAction('#')
            ->add('user',new UserType(), array(
            ))
            ->add('description','text', array(
                'label' => 'Description',
                'attr' => array(
                    'class' => 'form-control h1',
                    'placeholder' => 'Enter description',
                ),
            ))
            ->add('create','submit', array('label' => 'Create'))
            ->getForm();

        if (!is_null($request)) {
            $form->handleRequest($request);
        };

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /** @var $user User */
            $user = $persona->getUser();
            $user->setEmail($user->getUsername());
            $user->setPassword('none');
            $user->setLastLogin(new \DateTime(date('Y-m-d H:i:s',time())));
            $em->persist($user);
            $em->persist($persona);
            $em->flush();

            return $this->redirect($this->generateUrl('persona_affinity', array('id'=>$persona->getId())));
        }

        return $this->render('LaLearnodexBundle:Persona:new.html.twig',array(
            'form'      =>$form->createView(),
        ));
    }

    public function affinityAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        /** @var $persona Persona */
        if ($id) {
            $persona = $em->getRepository('LaCoreBundle:Persona')->find($id);
        } else {
            throw $this->createNotFoundException( 'No persona found for id ' . $id );
        }

        $agoras = $em->getRepository('LaCoreBundle:Agora')->findAll();
        $affinities = $persona->getUser()->getAffinities();

        return $this->render('LaLearnodexBundle:Persona:affinities.html.twig',array(
            'persona'       =>$persona,
            'agoras'        =>$agoras,
            'affinities'    => $affinities,
        ));
    }
}
