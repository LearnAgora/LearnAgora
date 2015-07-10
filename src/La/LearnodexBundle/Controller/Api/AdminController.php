<?php

namespace La\LearnodexBundle\Controller\Api;


use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Action;
use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\Answer;
use La\CoreBundle\Entity\AnswerOutcome;
use La\CoreBundle\Entity\ButtonOutcome;
use La\CoreBundle\Entity\SimpleUrlQuestion;
use La\CoreBundle\Entity\LearningEntity;
use La\CoreBundle\Entity\Techne;
use La\CoreBundle\Entity\Uplink;
use La\CoreBundle\Entity\UrlOutcome;
use La\CoreBundle\Entity\User;
use La\LearnodexBundle\Model\Card;
use La\LearnodexBundle\Model\Visitor\ParseJsonVisitor;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpFoundation\Request;
use La\CoreBundle\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use La\CoreBundle\Event\LearningEntityChangedEvent;

class AdminController extends Controller
{
    /**
     * @var EventDispatcherInterface
     *
     * @DI\Inject("event_dispatcher")
     */
    private $eventDispatcher;


    /**
     * @var SecurityContextInterface
     *
     * @DI\Inject("security.context")
     */
    private $securityContext;


    /**
     * @param Request $request
     *
     * @return View
     *
     * @throws NotFoundHttpException if techne agora cannot be found
     *
     * @Doc\ApiDoc(
     *  section="Learnodex",
     *  description="Retrieves the techne agora for the current user",
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when no techne agora is found",
     *  })
     */
    public function saveAction(Request $request)
    {
        /** @var User $user */
        //$user = $this->securityContext->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $json_string = $request->getContent();
        $json_data = json_decode($json_string);//get the response data as array

        $jsonEntity = $json_data->entity;
        $id = $jsonEntity->id;

        /** @var $learningEntity LearningEntity */
        $learningEntity = $em->getRepository('LaCoreBundle:LearningEntity')->find($id);
        $learningEntity->setName($jsonEntity->name);
        $em->persist($learningEntity);

        $parseJsonVisitor = new ParseJsonVisitor($jsonEntity,$em);
        $learningEntity->accept($parseJsonVisitor);

        $this->eventDispatcher->dispatch(Events::LEARNING_ENTITY_CHANGED, new LearningEntityChangedEvent($learningEntity));

        /*
        "{'entity':{
            'id':52,
            'name':'Action6 For Agora6 - Something Interesting',
            'discr':'action',
            '_embeddedItems':{
                'content':{
                    'id':52,
                    'instruction':'read and answer changed',
                    'question':'select a',
                    'url':'http:\/\/www.google.be',
                    'discr':'urlsimple',
                    '_links':{
                        'answers':{
                            'href':'\/sandbox\/content\/52\/answers'
                        }
                    },
                    '_embeddedItems':{
                        'answers':[
                            {
                                'id':181,
                                'answer':'a',
                                '_links':{
                                    'self':{
                                        'href':'\/sandbox\/answer\/181'
                                    }
                                }
                            },
                            {
                                'id':182,
                                'answer':'b',
                                '_links':{
                                    'self':{
                                        'href':'\/sandbox\/answer\/182'
                                    }
                                }
                            },
                            {'id':183,'answer':'wrong','_links':{'self':{'href':'\/sandbox\/answer\/183'}}},
                            {'id':184,'answer':'wrong','_links':{'self':{'href':'\/sandbox\/answer\/184'}}}
                        ]
                    }
                },
                'outcomes':[
                    {
                        'id':316,
                        'affinity':0,
                        'caption':'DISCARD',
                        'subject':'button'
                    },
                    {'id':317,'affinity':40,'caption':'LATER','subject':'button'},
                    {'id':318,'affinity':60,'subject':'url'},
                    {'id':319,'affinity':100,'selected':1,'answer':{'id':181,'answer':'a','_links':{'self':{'href':'\/sandbox\/answer\/181'}}},'subject':'answer'},
                    {'id':320,'affinity':40,'selected':1,'answer':{'id':182,'answer':'b','_links':{'self':{'href':'\/sandbox\/answer\/182'}}},'subject':'answer'},
                    {'id':321,'affinity':0,'selected':1,'answer':{'id':183,'answer':'wrong','_links':{'self':{'href':'\/sandbox\/answer\/183'}}},'subject':'answer'},
                    {'id':322,'affinity':0,'selected':1,'answer':{'id':184,'answer':'wrong','_links':{'self':{'href':'\/sandbox\/answer\/184'}}},'subject':'answer'}
                ]
            }
        }}"
        */

        $card = new Card($learningEntity);
        return View::create($card, 200);
    }

    /**
     * @param Request $request
     * @param String $type
     *
     * @return View
     *
     * @throws NotFoundHttpException if techne agora cannot be found
     *
     * @Doc\ApiDoc(
     *  section="Learnodex",
     *  description="Retrieves the techne agora for the current user",
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when no techne agora is found",
     *  })
     */
    public function createAction(Request $request, $type)
    {
        /** @var User $user */
        $user = $this->securityContext->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $learningEntity = null;

        switch ($type) {
            case "action":
                $learningEntity = new Action();
                break;
            case "agora":
                $learningEntity = new Agora();
                break;
            case "techne":
                $learningEntity = new Techne();
                break;
        }

        if (is_null($learningEntity)) {
            return View::create("Could not create Entity with type $type", 404);
        }


        $json_string = $request->getContent();
        $json_data = json_decode($json_string);//get the response data as array

        $jsonEntity = $json_data->entity;

        /** @var $learningEntity Action */
        $learningEntity->setOwner($user);
        $learningEntity->setName($jsonEntity->name);
        $em->persist($learningEntity);

        $parseJsonVisitor = new ParseJsonVisitor($jsonEntity,$em,true);
        $learningEntity->accept($parseJsonVisitor);

        $this->eventDispatcher->dispatch(Events::LEARNING_ENTITY_CHANGED, new LearningEntityChangedEvent($learningEntity));

        $card = new Card($learningEntity);
        return View::create($card, 200);
    }

    public function uplinkAction($childId, $parentId) {
        $em = $this->getDoctrine()->getManager();

        /** @var $childEntity LearningEntity */
        $childEntity = $em->getRepository('LaCoreBundle:LearningEntity')->find($childId);
        /** @var $parentEntity LearningEntity */
        $parentEntity = $em->getRepository('LaCoreBundle:LearningEntity')->find($parentId);

        $upLink = new Uplink();
        $upLink->setParent($parentEntity);
        $upLink->setChild($childEntity);
        $upLink->setWeight(0);

        $em->persist($upLink);
        $em->flush();

        return View::create(null, 204);
    }

}
