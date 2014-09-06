<?php

namespace La\LearnodexBundle\Controller;

use La\CoreBundle\Entity\LearningEntity;
use La\CoreBundle\Entity\Outcome;
use La\CoreBundle\Entity\Uplink;
use La\CoreBundle\Entity\User;
use La\CoreBundle\Model\ContentVisitor;
use La\CoreBundle\Model\PossibleOutcomeVisitor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class AdminController extends Controller
{
    public function indexAction(Request $request,$id)
    {
    }

    public function newAction(Request $request,$type)
    {
        /** @var $user User */
        $user = $this->get('security.context')->getToken()->getUser();

        //check type
        if (!in_array($type,array("Agora","Objective","Action"))) {
            throw $this->createNotFoundException(
                'Invalid learning entity type '.$type
            );
        }

        /** @var $learningEntity LearningEntity */
        $className = "La\\CoreBundle\\Entity\\" . $type;
        if (class_exists($className)) {
            $learningEntity = new $className;
        } else {
            throw $this->createNotFoundException(
                'Class ' . $className . ' not found'
            );
        }

        $form = $this->createFormBuilder($learningEntity)
            ->setAction($this->generateUrl('new_card', array('type'=>$type)))
            ->add('name','text', array(
                'label' => 'Name',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Enter ' . $type . ' name',
                ),
                'label_attr'=> array('class'=>'sr-only'),
            ))
            ->add('create','submit', array('label' => ('Create ') . $type))
            ->getForm();

        if (!is_null($request)) {
            $form->handleRequest($request);
        };

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $learningEntity->setOwner($user);
            $em->persist($learningEntity);
            $em->flush();

            return $this->redirect($this->generateUrl('card_content', array('id'=>$learningEntity->getId())));
        }

        return $this->render('LaLearnodexBundle:Admin:new.html.twig',array(
            'form'      =>$form->createView(),
            'learningEntity' => $learningEntity,
            'userName' => $user->getUserName(),
        ));
    }

    public function nameAction(Request $request, $id)
    {
        /** @var $user User */
        $user = $this->get('security.context')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        /** @var $learningEntity LearningEntity */
        $learningEntity = $em->getRepository('LaCoreBundle:LearningEntity')->find($id);

        if (!$learningEntity) {
            throw $this->createNotFoundException(
                'No entity found for id ' . $id
            );
        }

        $form = $this->createFormBuilder($learningEntity)
            ->setAction($this->generateUrl('card_name', array('id'=>$id)))
            ->add('name','text', array(
                'label' => 'Name',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Enter name',
                ),
                'label_attr'=> array('class'=>'sr-only'),
            ))
            ->add('create','submit', array('label' => 'Save'))
            ->getForm();

        if (!is_null($request)) {
            $form->handleRequest($request);
        };

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($learningEntity);
            $em->flush();

            return $this->redirect($this->generateUrl('card_content', array('id'=>$learningEntity->getId())));
        }

        return $this->render('LaLearnodexBundle:Admin:name.html.twig',array(
            'form'      =>$form->createView(),
            'learningEntity' => $learningEntity,
            'userName' => $user->getUserName(),
        ));
    }
    public function contentAction(Request $request, $id)
    {
        /** @var $user User */
        $user = $this->get('security.context')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        /** @var $learningEntity LearningEntity */
        $learningEntity = $em->getRepository('LaCoreBundle:LearningEntity')->find($id);

        if (!$learningEntity) {
            throw $this->createNotFoundException(
                'No entity found for id ' . $id
            );
        }

        $contentVisitor = new ContentVisitor();
        $content = $learningEntity->accept($contentVisitor);

        $form = $this->createFormBuilder($content)
            ->setAction($this->generateUrl('card_content', array('id'=>$id)))
            ->add('content','textarea', array(
                'label' => 'Name',
                'attr' => array(
                    'rows' => 10,
                    'class' => 'form-control',
                    'value' => $content->getContent(),
                    'placeholder' => 'Enter description',
                ),
                'label_attr'=> array('class'=>'sr-only'),
            ))
            ->add('create','submit', array('label' => 'Save'))
            ->getForm();

        if (!is_null($request)) {
            $form->handleRequest($request);
        };

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $learningEntity->setContent($content);
            $em->persist($content);
            $em->persist($learningEntity);
            $em->flush();

            return $this->redirect($this->generateUrl('card_outcome', array('id'=>$learningEntity->getId())));
        }

        return $this->render('LaLearnodexBundle:Admin:content.html.twig',array(
            'form'      =>$form->createView(),
            'learningEntity' => $learningEntity,
            'userName' => $user->getUserName(),
        ));
    }
    public function outcomeAction($id)
    {
        /** @var $user User */
        $user = $this->get('security.context')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        /** @var $learningEntity LearningEntity */
        $learningEntity = $em->getRepository('LaCoreBundle:LearningEntity')->find($id);

        if (!$learningEntity) {
            throw $this->createNotFoundException(
                'No entity found for id ' . $id
            );
        }

        /** @var $outcome Outcome */
        $outcomes = $learningEntity->getOutcomes();
        foreach ($outcomes as $outcome) {
            $outcome->setForm($this->createFormBuilder($outcome)
                ->setAction($this->generateUrl('update_outcome',array('outcomeId'=>$outcome->getId())))
                ->add('subject','text', array('label' => 'Subject','read_only' => true,'label_attr'=> array('class'=>'sr-only')))
                ->add('operator','choice', array('choices' => array(
                    '>' => 'is bigger than',
                    '<' => 'is smaller than'
                ),'label' => 'Operator','label_attr'=> array('class'=>'sr-only')))
                ->add('treshold','percent', array('label' => 'Treshold', 'max_length' => 2,'label_attr'=> array('class'=>'sr-only')))
                ->add('create','submit', array('label' => 'update'))
                ->getForm()
                ->createView()
            );
        }

        $possibleOutcomeVisitor = new PossibleOutcomeVisitor();
        $possibleOutcomes = $learningEntity->accept($possibleOutcomeVisitor);
        foreach ($possibleOutcomes as $outcome) {
            $outcome->setForm($this->createFormBuilder($outcome)
                ->setAction($this->generateUrl('add_outcome',array('id'=>$id)))
                ->add('subject','text', array('label' => 'Subject','read_only' => true,'label_attr'=> array('class'=>'sr-only')))
                ->add('operator','choice', array('choices' => array(
                    '>' => 'is bigger than',
                    '<' => 'is smaller than'
                ),'label' => 'Operator','label_attr'=> array('class'=>'sr-only')))
                ->add('treshold','percent', array('label' => 'Treshold', 'max_length' => 2,'label_attr'=> array('class'=>'sr-only')))
                ->add('create','submit', array('label' => 'add outcome'))
                ->getForm()
                ->createView()
            );
        }

        return $this->render('LaLearnodexBundle:Admin:outcome.html.twig',array(
            'learningEntity'        => $learningEntity,
            'outcomes'              => $outcomes,
            'possibleOutcomes'      => $possibleOutcomes,
            'userName'              => $user->getUserName(),
        ));
    }
    public function addOutcomeAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var $learningEntity LearningEntity */
        $learningEntity = $em->getRepository('LaCoreBundle:LearningEntity')->find($id);

        if (!$learningEntity) {
            throw $this->createNotFoundException(
                'No entity found for id ' . $id
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

        return $this->redirect($this->generateUrl('card_outcome', array('id'=>$learningEntity->getId())));
    }
    public function updateOutcomeAction(Request $request, $outcomeId)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var $outcome Outcome */
        $outcome = $em->getRepository('LaCoreBundle:Outcome')->find($outcomeId);

        if (!$outcome) {
            throw $this->createNotFoundException(
                'No outcome found for id ' . $outcomeId
            );
        }

        $form = $this->createFormBuilder($outcome)
            ->add('subject','text', array('label' => 'Subject','read_only' => true,'label_attr'=> array('class'=>'sr-only')))
            ->add('operator','choice', array('choices' => array(
                '>' => 'is bigger than',
                '<' => 'is smaller than'
            ),'label' => 'Operator','label_attr'=> array('class'=>'sr-only')))
            ->add('treshold','percent', array('label' => 'Treshold', 'max_length' => 2,'label_attr'=> array('class'=>'sr-only')))
            ->add('create','submit', array('label' => 'update'))
            ->getForm();

        if (!is_null($request)) {
            $form->handleRequest($request);
        };

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($outcome);
            $em->flush();
        }

        $learningEntity = $outcome->getLearningEntity();
        return $this->redirect($this->generateUrl('card_outcome', array('id'=>$learningEntity->getId())));
    }
    public function removeOutcomeAction($outcomeId)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var $outcome Outcome */
        $outcome = $em->getRepository('LaCoreBundle:Outcome')->find($outcomeId);

        if (!$outcome) {
            throw $this->createNotFoundException(
                'No outcome found for id ' . $outcomeId
            );
        }

        $learningEntity = $outcome->getLearningEntity();

        $em->remove($outcome);
        $em->flush();

        return $this->redirect($this->generateUrl('card_outcome', array('id'=>$learningEntity->getId())));
    }

    public function linkAction(Request $request, $id)
    {
        /** @var $user User */
        $user = $this->get('security.context')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        /** @var $learningEntity LearningEntity */
        $learningEntity = $em->getRepository('LaCoreBundle:LearningEntity')->find($id);

        if (!$learningEntity) {
            throw $this->createNotFoundException(
                'No entity found for id ' . $id
            );
        }

        $upLinks = $learningEntity->getUplinks();
        $downLinks = $learningEntity->getDownlinks();

        $allLearningEntities = $em->getRepository('LaCoreBundle:LearningEntity')->findAll();

        return $this->render('LaLearnodexBundle:Admin:link.html.twig',array(
            'learningEntity'      => $learningEntity,
            'upLinks'             => $upLinks,
            'downLinks'           => $downLinks,
            'allLearningEntities' => $allLearningEntities,
            'userName'            => $user->getUserName(),
        ));
    }

    public function addChildAction(Request $request, $parentId, $childId)
    {
        if (is_null($request)) {
            throw $this->createNotFoundException(
                'Cannot treat empty request '
            );
        }
        $em = $this->getDoctrine()->getManager();
        /** @var $parentEntity LearningEntity */
        $parentEntity = $em->getRepository('LaCoreBundle:LearningEntity')->find($parentId);
        /** @var $childEntity LearningEntity */
        $childEntity = $em->getRepository('LaCoreBundle:LearningEntity')->find($childId);
        $weight = $request->request->get('weight');

        $upLink = new Uplink();
        $upLink->setParent($parentEntity);
        $upLink->setChild($childEntity);
        $upLink->setWeight($weight);

        $em->persist($upLink);
        $em->flush();

        return $this->redirect($this->generateUrl('card_link', array('id'=>$parentId)));
    }
}
