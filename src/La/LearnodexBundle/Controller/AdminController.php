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
use La\CoreBundle\Entity\QuestionContent;
use La\CoreBundle\Entity\Uplink;
use La\CoreBundle\Entity\User;
use La\CoreBundle\Event\LearningEntityChangedEvent;
use La\CoreBundle\Model\Content\GetNameVisitor;
use La\LearnodexBundle\Model\Visitor\GetIncludeTwigVisitor;
use La\LearnodexBundle\Model\Visitor\InitialiseLearningEntityVisitor;
use La\CoreBundle\Model\ContentVisitor;
use La\LearnodexBundle\Model\Card;
use La\LearnodexBundle\Model\Visitor\GetContentFormVisitor;
use La\LearnodexBundle\Model\Visitor\GetOutcomeIncludeTwigVisitor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use La\CoreBundle\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


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

    /**
     * @var EventDispatcherInterface
     *
     * @DI\Inject("event_dispatcher")
     */
    private $eventDispatcher;

    public function indexAction()
    {
        /** @var $user User */
        $user = $this->get('security.context')->getToken()->getUser();
        $learningEntities = $user->getLearningEntities();

        //sort learningEntities per class, i guess there are better patterns for this
        $techne = array();
        $agoras = array();
        $objectives = array();
        $actions = array();
        foreach ($learningEntities as $learningEntity) {
            if (is_a($learningEntity,'La\CoreBundle\Entity\Techne')) {
                $techne[] = $learningEntity;
            }
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
            'techne'    =>  $techne
        ));
    }

    public function newAction(Request $request,$type)
    {
        //check type
        if (!in_array($type,array("Techne","Agora","Objective","Action"))) {
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

        /* @var $content Content */
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

            $this->eventDispatcher->dispatch(Events::LEARNING_ENTITY_CHANGED, new LearningEntityChangedEvent($learningEntity));

            if (!is_null($request->request->get('add_answer'))) {
                /* @var QuestionContent $content  */
                $answer = new Answer();
                $answer->setQuestion($content);
                $content->addAnswer($answer);
                $outcome = new AnswerOutcome();
                $outcome->setAnswer($answer);
                $outcome->setSelected(1);
                $outcome->setLearningEntity($learningEntity);
                $outcome->setAffinity(0);
                $em->persist($outcome);
                $em->persist($answer);
            }
            if (!is_null($request->request->get('remove_answer'))) {
                $answerId = $request->request->get('remove_answer');
                /* @var Answer $answer */
                $answer = $em ->getRepository('LaCoreBundle:Answer')->find($answerId);
                if (!$answer) {
                    throw $this->createNotFoundException(
                        'No answer found for id ' . $answerId
                    );
                }

                $outcomes = $answer->getOutcomes();
                foreach ($outcomes as $outcome) {
                    /* @var Outcome $outcome */
                    foreach ($outcome->getProbabilities() as $outcomeProbability) {
                        $em->remove($outcomeProbability);
                    }
                    $em->remove($outcome);
                }
                $em->remove($answer);
            }

            $em->flush();

            return $this->redirect($this->generateUrl('card_content', array('id'=>$learningEntity->getId())));
        }

        $card = new Card($learningEntity);
        return $this->render($card->getContentTwig(),array(
            'card'      => $card,
            'form'      => $form->createView(),
        ));
    }

    public function addAnswerAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var $learningEntity LearningEntity */
        $learningEntity = $em->getRepository('LaCoreBundle:LearningEntity')->find($id);

        if (!$learningEntity) {
            throw $this->createNotFoundException(
                'No entity found for id ' . $id
            );
        }

        /* @var QuestionContent $content  */
        $content = $learningEntity->getContent();
        $answer = new Answer();
        $answer->setQuestion($content);
        $content->addAnswer($answer);
        $outcome = new AnswerOutcome();
        $outcome->setAnswer($answer);
        $outcome->setSelected(1);
        $outcome->setLearningEntity($learningEntity);
        $outcome->setAffinity(0);
        $em->persist($outcome);
        $em->persist($content);
        $em->persist($answer);
        $em->flush();

        return $this->redirect($this->generateUrl('card_content', array('id'=>$id)));
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

        $outcomes = $answer->getOutcomes();
        foreach ($outcomes as $outcome) {
            $em->remove($outcome);
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
            $outcome->setAnswer($answer);
            $outcome->setSelected($selected);
            $outcome->setLearningEntity($learningEntity);
            $outcome->setAffinity($affinity);

            $em->persist($outcome);
            $em->flush();
        };


        return $this->redirect($this->generateUrl('card_outcome', array('id'=>$id)));
    }
    public function setOutcomeAffinityAction($outcomeId, $affinity)
    {
        /* @var Outcome $outcome */
        $outcome = $this->outcomeRepository->find($outcomeId);
        if (!$outcome) {
            throw $this->createNotFoundException(
                'No outcome found for id ' . $outcomeId
            );
        }

        $outcome->setAffinity($affinity);

        $this->entityManager->persist($outcome);
        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(Events::LEARNING_ENTITY_CHANGED, new LearningEntityChangedEvent($outcome->getLearningEntity()));

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

    public function usersAction() {
        $users = $this->entityManager->getRepository('LaCoreBundle:User')->findBy(array('enabled'=>'1'));
        return $this->render('LaLearnodexBundle:Admin:Users/Users.html.twig',array(
            'users'                => $users
        ));
    }
    public function makeAdminAction($id) {
        /** @var User $user */
        $user = $this->entityManager->getRepository('LaCoreBundle:User')->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id ' . $id
            );
        }

        $user->addRole("ROLE_ADMIN");
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->redirect($this->generateUrl('manage_user_roles'));
    }
    public function makeUserAction($id) {
        /** @var User $user */
        $user = $this->entityManager->getRepository('LaCoreBundle:User')->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id ' . $id
            );
        }

        $user->removeRole("ROLE_ADMIN");
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->redirect($this->generateUrl('manage_user_roles'));
    }
}
