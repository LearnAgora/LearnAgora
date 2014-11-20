<?php

namespace La\LearnodexBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Answer;
use La\CoreBundle\Entity\AnswerOutcome;
use La\CoreBundle\Entity\LearningEntity;
use La\CoreBundle\Entity\Content;
use La\CoreBundle\Entity\Outcome;
use La\CoreBundle\Entity\SimpleUrlQuestion;
use La\CoreBundle\Entity\Uplink;
use La\CoreBundle\Entity\User;
use La\CoreBundle\Model\Content\GetNameVisitor;
use La\LearnodexBundle\Model\UpdateAllAffinities;
use La\LearnodexBundle\Model\Visitor\GetIncludeTwigVisitor;
use La\LearnodexBundle\Model\Visitor\InitialiseLearningEntityVisitor;
use La\CoreBundle\Model\LearningEntity\TwigOutcomeVisitor;
use La\CoreBundle\Model\Outcome\GetOutcomeFormVisitor;
use La\CoreBundle\Model\ContentVisitor;
use La\CoreBundle\Model\Outcome\GetTwigForOutcomeVisitor;
use La\CoreBundle\Model\PossibleOutcomeVisitor;
use La\LearnodexBundle\Model\Card;
use La\LearnodexBundle\Model\Visitor\GetContentFormVisitor;
use La\LearnodexBundle\Model\Visitor\GetOutcomeIncludeTwigVisitor;
use La\LearnodexBundle\Model\Visitor\UpLinkManagerVisitor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class AdminController extends Controller
{
    /**
     * @var ObjectManager $entityManager
     *
     *  @DI\Inject("doctrine.orm.entity_manager"),
     */
    private $entityManager;
    /**
     * @var ObjectRepository
     *
     * @DI\Inject("la_core.repository.learning_entity")
     */
    private $learningEntityRepository;

    /**
     * @var ObjectRepository
     *
     * @DI\Inject("la_core.repository.outcome")
     */
    private $outcomeRepository;

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

        return $this->render('LaLearnodexBundle:Admin:Index.html.twig', array(
            'agoras'     => $agoras,
            'objectives' => $objectives,
            'actions'    => $actions,
        ));
    }

    public function newAction(Request $request,$type)
    {
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
            $user = $this->get('security.context')->getToken()->getUser();
            $learningEntity->setOwner($user);
            /* @var InitialiseLearningEntityVisitor $initialiseLearningEntityVisitor */
            $initialiseLearningEntityVisitor = $this->get('la_learnodex.initialise_learning_entity_visitor');
            $learningEntity->accept($initialiseLearningEntityVisitor);

            return $this->redirect($this->generateUrl('card_content', array('id'=>$learningEntity->getId())));
        }

        return $this->render('LaLearnodexBundle:Admin:New.html.twig',array(
            'form'      =>$form->createView(),
            'learningEntity' => $learningEntity,
        ));
    }

    public function nameAction(Request $request, $id)
    {
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

        $card = new Card($learningEntity);
        return $this->render('LaLearnodexBundle:Admin:Name.html.twig',array(
            'form'      =>$form->createView(),
            'card'      => $card,
        ));
    }
    public function selectContentAction($id, $type="")
    {
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
            $className = "La\\CoreBundle\\Entity\\" . $type;
            if (class_exists($className)) {
                $content = new $className;
            } else {
                throw $this->createNotFoundException(
                    'Class for ' . $type . ' not found'
                );
            }

            $content->init($em);
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

        $card = new Card($learningEntity);
        return $this->render('LaLearnodexBundle:Admin:SelectContent.html.twig',array(
            'contentList'      => $contentList,
            'getNameVisitor'   => $getNameVisitor,
            'card'             => $card,
        ));
    }
    public function contentAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var $learningEntity LearningEntity */
        $learningEntity = $em->getRepository('LaCoreBundle:LearningEntity')->find($id);

        if (!$learningEntity) {
            throw $this->createNotFoundException( 'No entity found for id ' . $id );
        }

        $content = $learningEntity->getContent();
        if (is_null($content)) {
            $contentVisitor = new ContentVisitor();
            $contentList = $learningEntity->accept($contentVisitor);
            if (count($contentList) > 1) {
                return $this->redirect($this->generateUrl('card_content_select', array('id'=>$learningEntity->getId())));
            }
            $content = $contentList[0];
            $content->init($em);
        }

        $getContentFormVisitor = new GetContentFormVisitor();
        $form = $this->createForm($learningEntity->accept($getContentFormVisitor), $content);

        if (!is_null($request)) {
            $form->handleRequest($request);
        };

        if ($form->isValid()) {
            $learningEntity->setContent($content);
            $em->persist($content);
            $em->persist($learningEntity);
            $em->flush();

//            return $this->redirect($this->generateUrl('card_outcome', array('id'=>$learningEntity->getId())));
            return $this->redirect($this->generateUrl('card_content', array('id'=>$learningEntity->getId())));
        }

        $card = new Card($learningEntity);
        return $this->render($card->getContentTwig(),array(
            'card'      => $card,
            'form'      => $form->createView(),
        ));
    }
    public function addAnswerAction(Request $request, $id)
    {
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

        $card = new Card($learningEntity);
        return $this->render('LaLearnodexBundle:Admin:Content/AddAnswer.html.twig',array(
            'card'                  => $card,
            'form'                  => $form->createView(),
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

    public function outcomeAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var $learningEntity LearningEntity */
        $learningEntity = $em->getRepository('LaCoreBundle:LearningEntity')->find($id);
        if (!$learningEntity) {
            throw $this->createNotFoundException(
                'No entity found for id ' . $id
            );
        }

        $card = new Card($learningEntity);

        return $this->render('LaLearnodexBundle:Admin:Outcome/Outcome.html.twig',array(
            'card'              => $card,
            'cardOutcomes'      => $card->getOutcomes(),
            'twigVisitor'       => new GetOutcomeIncludeTwigVisitor(),
            'getIncludeTwigVisitor' => new GetIncludeTwigVisitor(),
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

        if (!is_null($request)) {
            $affinity = $request->request->get('affinity');
            $answerId = $request->request->get('answer');
            $selected = $request->request->get('selected');
            $answer = $em->getRepository('LaCoreBundle:Answer')->find($answerId);
            if (!$learningEntity) {
                throw $this->createNotFoundException(
                    'No answer found for id ' . $id
                );
            }

            $outcome = new AnswerOutcome();
            $result = new AffinityResult();
            $result->setValue($affinity);
            $result->setOutcome($outcome);
            $outcome->addResult($result);
            $outcome->setAnswer($answer);
            $outcome->setSelected($selected);
            $outcome->setLearningEntity($learningEntity);

            $em->persist($outcome);
            $em->persist($result);
            $em->flush();
        };


        return $this->redirect($this->generateUrl('card_outcome', array('id'=>$id)));
    }
    public function setOutcomeAffinityAction($outcomeId, $affinity)
    {
        $outcome = $this->outcomeRepository->find($outcomeId);
        if (!$outcome) {
            throw $this->createNotFoundException(
                'No outcome found for id ' . $outcomeId
            );
        }

        $outcome->setAffinity($affinity);

        $this->entityManager->persist($outcome);
        $this->entityManager->flush();

        return $this->redirect($this->generateUrl('card_outcome', array('id'=>$outcome->getLearningEntity()->getId())));
    }
    public function setOutcomeProgressAction($outcomeId, $progress)
    {
        $outcome = $this->outcomeRepository->find($outcomeId);
        if (!$outcome) {
            throw $this->createNotFoundException(
                'No outcome found for id ' . $outcomeId
            );
        }

        if ($progress == 'null') {
            $outcome->setProgress(null);
        } else {
            $outcome->setProgress($progress);
        }

        $this->entityManager->persist($outcome);
        $this->entityManager->flush();

        return $this->redirect($this->generateUrl('card_outcome', array('id'=>$outcome->getLearningEntity()->getId())));
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


    public function linkAction($id)
    {
        /** @var $learningEntity LearningEntity */
        $learningEntity = $this->learningEntityRepository->find($id);

        if (!$learningEntity) {
            throw $this->createNotFoundException(
                'No entity found for id ' . $id
            );
        }

        $upLinkManagerVisitor = $this->get('la_core.uplink_manager_visitor');
        $learningEntity->accept($upLinkManagerVisitor);

        $upLinks = $learningEntity->getUplinks();
        $downLinks = $learningEntity->getDownlinks();

        $card = new Card($learningEntity);
        return $this->render('LaLearnodexBundle:Admin:Links/Link.html.twig',array(
            'card'                => $card,
            'learningEntity'      => $learningEntity,
            'upLinks'             => $upLinks,
            'downLinks'           => $downLinks,
            'upLinkManager'       => $upLinkManagerVisitor,
        ));
    }
    public function addLinkAction(Request $request, $id, $parentId, $childId)
    {
        if (is_null($request)) {
            throw $this->createNotFoundException(
                'Cannot treat empty request '
            );
        }

        /** @var $parentEntity LearningEntity */
        $parentEntity = $this->learningEntityRepository->find($parentId);
        /** @var $childEntity LearningEntity */
        $childEntity = $this->learningEntityRepository->find($childId);
        $weight = $request->request->get('weight');

        $upLink = new Uplink();
        $upLink->setParent($parentEntity);
        $upLink->setChild($childEntity);
        $upLink->setWeight($weight);

        $this->entityManager->persist($upLink);
        $this->entityManager->flush();

        $this->get('la_learnodex.update_all_affinities');

        return $this->redirect($this->generateUrl('card_link', array('id'=>$id)));
    }

    public function editLinkAction(Request $request, $id, $linkId)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var $learningEntity LearningEntity */
        $learningEntity = $this->learningEntityRepository->find($id);
        $card = new Card($learningEntity);
        $link = $em->getRepository('LaCoreBundle:UpLink')->find($linkId);

        $form = $this->createFormBuilder($link)
            ->setAction($this->generateUrl('edit_link', array('id'=>$id, 'linkId'=>$linkId)))
            ->add('weight','integer', array(
                'label' => 'Weight',
                'attr' => array(
                    'class' => '',
                    'placeholder' => 'Enter weight',
                ),
                'label_attr'=> array('class'=>'sr-only'),
            ))
            ->add('create','submit', array('label' => 'update weight'))
            ->getForm();

        if (!is_null($request)) {
            $form->handleRequest($request);
        };

        if ($form->isValid()) {
            $this->entityManager->persist($link);
            $this->entityManager->flush();

            $this->get('la_learnodex.update_all_affinities');

            return $this->redirect($this->generateUrl('card_link', array('id'=>$id)));
        }

        return $this->render('LaLearnodexBundle:Admin:Links/EditLink.html.twig',array(
            'card'                => $card,
            'link'                => $link,
            'form'                =>$form->createView(),
        ));
    }
    public function removeLinkAction($id, $linkId)
    {
        $em = $this->getDoctrine()->getManager();
        $link = $em->getRepository('LaCoreBundle:UpLink')->find($linkId);
        $em->remove($link);
        $em->flush();

        $this->get('la_learnodex.update_all_affinities');

        return $this->redirect($this->generateUrl('card_link', array('id'=>$id)));
    }
}
