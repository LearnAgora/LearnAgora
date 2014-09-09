<?php

namespace La\LearnodexBundle\Controller;

use La\CoreBundle\Entity\AffinityOutcome;
use La\CoreBundle\Entity\Answer;
use La\CoreBundle\Entity\LearningEntity;
use La\CoreBundle\Entity\Content;
use La\CoreBundle\Entity\Outcome;
use La\CoreBundle\Entity\Uplink;
use La\CoreBundle\Entity\User;
use La\CoreBundle\Model\Content\GetAdminFormVisitor;
use La\CoreBundle\Model\Content\GetNameVisitor;
use La\CoreBundle\Model\Content\TwigContentVisitor;
use La\CoreBundle\Model\LearningEntity\TwigOutcomeVisitor;
use La\CoreBundle\Model\Outcome\GetOutcomeFormVisitor;
use La\CoreBundle\Model\ContentVisitor;
use La\CoreBundle\Model\Outcome\GetTwigForOutcomeVisitor;
use La\CoreBundle\Model\PossibleOutcomeVisitor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class AdminController extends Controller
{
    public function indexAction()
    {
        /** @var $user User */
        $user = $this->get('security.context')->getToken()->getUser();

        $learningEntities = $user->getLearningEntities();

        //sort learningEntities per class, i guess there are better patterns for this
        $agoras = array();
        $objectives = array();
        $actions = array();
        foreach ($learningEntities as $learningEntity) {
            if (is_a($learningEntity,'La\CoreBundle\Entity\Agora')) {
                $agoras[] = $learningEntity;
            }
            if (is_a($learningEntity,'La\CoreBundle\Entity\Objective')) {
                $objectives[] = $learningEntity;
            }
            if (is_a($learningEntity,'La\CoreBundle\Entity\Action')) {
                $actions[] = $learningEntity;
            }
        }

        return $this->render('LaLearnodexBundle:Admin:index.html.twig', array(
            'userName'   => $user->getUserName(),
            'agoras'     => $agoras,
            'objectives' => $objectives,
            'actions'    => $actions,
        ));
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
                    'class' => 'form-control h1',
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
    public function selectContentAction($id, $type="")
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

        /** @var $content Content */
        $content = null;
        if ($type != '') {
            //check type
            if (!in_array($type,array("HtmlContent","UrlContent","QuestionContent","QuizContent"))) {
                throw $this->createNotFoundException(
                    'Invalid content type '.$type
                );
            }

            $className = "La\\CoreBundle\\Entity\\" . $type;
            if (class_exists($className)) {
                $content = new $className;
            } else {
                throw $this->createNotFoundException(
                    'Class ' . $className . ' not found'
                );
            }

            $content->init();
            $learningEntity->setContent($content);
            $em = $this->getDoctrine()->getManager();
            $em->persist($content);
            $em->persist($learningEntity);
            $em->flush();

            return $this->redirect($this->generateUrl('card_content', array('id'=>$learningEntity->getId())));
        }

        $contentVisitor = new ContentVisitor();
        $contentList = $learningEntity->accept($contentVisitor);
        $getNameVisitor = new GetNameVisitor();

        return $this->render('LaLearnodexBundle:Admin:selectcontent.html.twig',array(
            'contentList'      => $contentList,
            'getNameVisitor'   => $getNameVisitor,
            'learningEntity'   => $learningEntity,
            'userName'         => $user->getUserName(),
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

        $content = $learningEntity->getContent();
        if (is_null($content)) {
            $contentVisitor = new ContentVisitor();
            $contentList = $learningEntity->accept($contentVisitor);
            if (count($contentList) > 1) {
                return $this->redirect($this->generateUrl('card_content_select', array('id'=>$learningEntity->getId())));
            }
            $content = $contentList[0];
        }

        $adminFormVisitor = new GetAdminFormVisitor();
        $form = $this->createForm($content->accept($adminFormVisitor), $content);

        if (!is_null($request)) {
            $form->handleRequest($request);
        };

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $learningEntity->setContent($content);
            $em->persist($content);
            $em->persist($learningEntity);
            $em->flush();

//            return $this->redirect($this->generateUrl('card_outcome', array('id'=>$learningEntity->getId())));
            return $this->redirect($this->generateUrl('card_content', array('id'=>$learningEntity->getId())));
        }

        $twigContentVisitor = new TwigContentVisitor();
        $twig = $content->accept($twigContentVisitor);

        return $this->render($twig,array(
            'form'      =>$form->createView(),
            'learningEntity' => $learningEntity,
            'userName' => $user->getUserName(),
        ));
    }
    public function addAnswerAction(Request $request, $id)
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

        $answer = new Answer();
        $form = $this->createFormBuilder($answer)
            ->setAction($this->generateUrl('add_answer', array('id'=>$id)))
            ->add('answer','textarea', array(
                'label' => 'Name',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Enter answer',
                ),
                'label_attr'=> array('class'=>'sr-only'),
            ))
            ->add('create','submit', array('label' => 'Save Answer'))
            ->getForm();

        if (!is_null($request)) {
            $form->handleRequest($request);
        };

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $content = $learningEntity->getContent();
            $answer->setQuestion($content);
            $em->persist($content);
            $em->persist($answer);
            $em->flush();

//            return $this->redirect($this->generateUrl('card_outcome', array('id'=>$learningEntity->getId())));
            return $this->redirect($this->generateUrl('card_content', array('id'=>$learningEntity->getId())));
        }

        return $this->render('LaLearnodexBundle:Admin:addAnswer.html.twig',array(
            'learningEntity'        => $learningEntity,
            'form'                  => $form->createView(),
            'userName'              => $user->getUserName(),
        ));
    }
    public function removeAnswerAction($id, $answerId)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var $answer Answer */
        $answer = $em ->getRepository('LaCoreBundle:Answer')->find($answerId);
        if (!$answer) {
            throw $this->createNotFoundException(
                'No answer found for id ' . $answerId
            );
        }

        $em->remove($answer);
        $em->flush();

        return $this->redirect($this->generateUrl('card_content', array('id'=>$id)));
    }

    public function outcomeAction(Request $request, $id)
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

        if (!is_null($request)) {
            //Why is this not working??
            $type = $request->request->get('create');
//            $type = "AffinityOutcome";
            $type = "AnswerOutcome";
            $className = "La\\CoreBundle\\Entity\\" . $type;
            if (class_exists($className)) {
                $outcome = new $className;
            } else {
                throw $this->createNotFoundException(
                    'Class ' . $className . ' not found'
                );
            }

            $outcomeFormVisitor = new GetOutcomeFormVisitor();
            $form = $this->createForm($outcome->accept($outcomeFormVisitor), $outcome);

            $form->handleRequest($request);

            if ($form->isValid()) {
                $outcome->setLearningEntity($learningEntity);
                $em = $this->getDoctrine()->getManager();
                $em->persist($outcome);
                $em->flush();
            }
        };

        /*
        $outcomes = $learningEntity->getOutcomes();
        $outcomeFormVisitor = new GetOutcomeFormVisitor();
        foreach ($outcomes as $outcome) {
            $form = $this->createForm($outcome->accept($outcomeFormVisitor), $outcome);
            $outcome->setForm($form->createView());
        }

        $possibleOutcomeVisitor = new PossibleOutcomeVisitor();
        $possibleOutcomes = $learningEntity->accept($possibleOutcomeVisitor);
        foreach ($possibleOutcomes as $outcome) {
            $form = $this->createForm($outcome->accept($outcomeFormVisitor), $outcome);
            $outcome->setForm($form->createView());
        }
*/
        $outcomes = array();
        $possibleOutcomes = array();

        $twigOutcomeVisitor = new TwigOutcomeVisitor();
        $twig = $learningEntity->accept($twigOutcomeVisitor);
        return $this->render($twig,array(
            'learningEntity'           => $learningEntity,
            'getTwigForOutcomeVisitor' => new GetTwigForOutcomeVisitor(),
            'possibleOutcomeVisitor'   => new PossibleOutcomeVisitor(),
            'getOutcomeFormVisitor'    => new GetOutcomeFormVisitor(),
            'userName'                 => $user->getUserName(),
        ));
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
