<?php

namespace La\LearnodexBundle\Controller;

use La\CoreBundle\Entity\Outcome;
use La\CoreBundle\Model\PossibleOutcomeVisitor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class CardController extends Controller
{
    public function indexAction(Request $request,$type, $id=0)
    {
        $user = $this->get('security.context')->getToken()->getUser();

        //check type
        if (!in_array($type,array("Agora","Objective","Action"))) {
            throw $this->createNotFoundException(
                'Invalid learning entity type '.$type
            );
        }

        if ($id>0) {
            $em = $this->getDoctrine()->getManager();
            $learningEntity = $em->getRepository('LaCoreBundle:'.$type)->find($id);

            if (!$learningEntity) {
                throw $this->createNotFoundException(
                    'No ' . $type . ' found for id ' . $id
                );
            }
        } else {
            $className = "La\\CoreBundle\\Entity\\" . $type;
            if (class_exists($className)) {
                $learningEntity = new $className;
            } else {
                throw $this->createNotFoundException(
                    'Class ' . $className . ' not found'
                );
            }
        }

        $parameters = array('type'=>$type);
        if ($id) {
            $parameters['id'] = $id;
        }

        $form = $this->createFormBuilder($learningEntity)
            ->setAction($this->generateUrl($id ? 'card' : 'new_card',$parameters))
            ->add('name','text', array('label' => 'Name'))
            ->add('description','text', array('label' => 'Description'))
            ->add('create','submit', array(
                'label' => ($id ? 'Save ' : 'Create ') . $type
            ))
            ->getForm();

        if (!is_null($request)) {
            $form->handleRequest($request);
        };

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($learningEntity);
            $em->flush();

            return $this->redirect($this->generateUrl('card_outcome', array('type'=>$type,'id'=>$learningEntity->getId())));
        }

        return $this->render('LaLearnodexBundle:Card:card.html.twig',array(
            'type'     => $type,
            'form'      =>$form->createView(),
            'learningEntity' => $learningEntity,
            'userName' => $user->getUserName(),
            'id'       =>$learningEntity->getId(),
        ));
    }

    public function outcomeAction(Request $request,$type, $id)
    {
        $user = $this->get('security.context')->getToken()->getUser();

        if ($id>0) {
            $em = $this->getDoctrine()->getManager();
            $learningEntity = $em->getRepository('LaCoreBundle:'.$type)->find($id);

            if (!$learningEntity) {
                throw $this->createNotFoundException(
                    'No ' . $type . ' found for id ' . $id
                );
            }
        } else {
            throw $this->createNotFoundException(
                'no id given'
            );
        }

        $possibleOutcomeVisitor = new PossibleOutcomeVisitor();
        $possibleOutcomes = $learningEntity->accept($possibleOutcomeVisitor);
        $possibleOutcomeForms = array();
        foreach ($possibleOutcomes as $outcome) {
            $possibleOutcomeForms[] = $this->createFormBuilder($outcome)
                ->setAction($this->generateUrl('add_outcome',array('id'=>$id,'type'=>$type)))
                ->add('subject','text', array('label' => 'Subject','read_only' => true))
                ->add('operator','choice', array('choices' => array(
                    '>' => 'is bigger than',
                    '<' => 'is smaller than'
                ),'label' => 'Operator'))
                ->add('treshold','percent', array('label' => 'Treshold', 'max_length' => 2))
                ->add('create','submit', array('label' => 'add outcome'))
                ->getForm()
                ->createView();
        }

        $outcomes = $learningEntity->getOutcomes();
        $outcomeForms = array();
        foreach ($outcomes as $outcome) {
            $outcomeForms[] = $this->createFormBuilder($outcome)
                ->setAction($this->generateUrl('add_outcome',array('id'=>$id,'type'=>$type)))
                ->add('subject','text', array('label' => 'Subject','read_only' => true))
                ->add('operator','choice', array('choices' => array(
                    '>' => 'is bigger than',
                    '<' => 'is smaller than'
                ),'label' => 'Operator'))
                ->add('treshold','percent', array('label' => 'Treshold', 'max_length' => 2))
                ->add('create','submit', array('label' => 'add outcome'))
                ->getForm()
                ->createView();
        }

        return $this->render('LaLearnodexBundle:Card:outcome.html.twig',array(
            'type'     => $type,
            'learningEntity' => $learningEntity,
            'id'       => $id,
            'outcomeForms' => $outcomeForms,
            'possibleOutcomeForms'    => $possibleOutcomeForms,
            'userName' => $user->getUserName(),
        ));
    }

    public function linkAction(Request $request,$type, $id)
    {
        $user = $this->get('security.context')->getToken()->getUser();

        return $this->render('LaLearnodexBundle:Card:link.html.twig',array(
            'type'     => $type,
            'id'       =>$id,
            'userName' => $user->getUserName(),
        ));
    }

    public function addOutcomeAction(Request $request, $type, $id){
        if ($id>0) {
            $em = $this->getDoctrine()->getManager();
            $learningEntity = $em->getRepository('LaCoreBundle:LearningEntity')->find($id);

            if (!$learningEntity) {
                throw $this->createNotFoundException(
                    'No Learning Entity found for id ' . $id
                );
            }
        } else {
            throw $this->createNotFoundException(
                'no id given'
            );
        }

        $outcome = new Outcome();

        $form = $this->createFormBuilder($outcome)
            ->add('subject','text', array('label' => 'Subject','read_only' => true))
            ->add('operator','choice', array('choices' => array(
                '>' => 'is bigger than',
                '<' => 'is smaller than'
            ),'label' => 'Operator'))
            ->add('treshold','percent', array('label' => 'Treshold', 'max_length' => 2))
            ->add('create','submit', array('label' => 'add outcome'))
            ->getForm();

        if (!is_null($request)) {
            $form->handleRequest($request);
        };

        if ($form->isValid()) {
            $outcome->setLearningEntity($learningEntity);
            $em = $this->getDoctrine()->getManager();
            $em->persist($outcome);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('card_outcome', array('type'=>$type,'id'=>$learningEntity->getId())));
    }
}
