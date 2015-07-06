<?php

namespace La\LearnodexBundle\Controller\Api;

use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\AgoraBase;
use La\CoreBundle\Entity\Answer;
use La\CoreBundle\Entity\AnswerOutcome;
use La\CoreBundle\Entity\Content;
use La\CoreBundle\Entity\QuestionContent;
use La\CoreBundle\Entity\Repository\ProfileRepository;
use La\CoreBundle\Entity\Repository\TechneRepository;
use La\CoreBundle\Entity\SimpleUrlQuestion;
use La\CoreBundle\Entity\Techne;
use La\CoreBundle\Entity\LearningEntity;
use La\CoreBundle\Entity\User;
use La\CoreBundle\Entity\UserProbability;
use La\LearnodexBundle\Forms\AnswerType;
use La\LearnodexBundle\Model\Card;
use La\LearnodexBundle\Model\Visitor\GetContentFormVisitor;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Hateoas\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * @var TechneRepository
     */
    private $techneRepository;

    /**
     * @var ProfileRepository
     */
    private $profileRepository;

    /**
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext
     * @param TechneRepository $techneRepository
     * @param ProfileRepository $profileRepository
     *
     * @DI\InjectParams({
     *     "securityContext" = @DI\Inject("security.context"),
     *     "techneRepository" = @DI\Inject("la_core.repository.techne"),
     *  "profileRepository" = @DI\Inject("la_core.repository.profile")
     * })
     */
    public function __construct(SecurityContextInterface $securityContext, TechneRepository $techneRepository, ProfileRepository $profileRepository)
    {
        $this->securityContext = $securityContext;
        $this->techneRepository = $techneRepository;
        $this->profileRepository = $profileRepository;
    }

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
        $user = $this->securityContext->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $json_string = $request->getContent();
        $json_data = json_decode($json_string);//get the response data as array

        $jsonEntity = $json_data->entity;
        $id = $jsonEntity->id;

        /** @var $learningEntity LearningEntity */
        $learningEntity = $em->getRepository('LaCoreBundle:LearningEntity')->find($id);
        $learningEntity->setName($jsonEntity->name);
        $em->persist($learningEntity);

        $jsonContent = $jsonEntity->_embeddedItems->content;
        /* @var $content SimpleUrlQuestion */
        $content = $learningEntity->getContent();
        $content->setInstruction($jsonContent->instruction);
        $content->setQuestion($jsonContent->question);
        $content->setUrl($jsonContent->url);
        $em->persist($content);

        $jsonOutcomes = $jsonEntity->_embeddedItems->outcomes;
        foreach ($jsonOutcomes as $jsonOutcome) {
            if ($jsonOutcome->subject == "answer") {
                /** @var $outcome AnswerOutcome */
                $outcome = $em->getRepository('LaCoreBundle:AnswerOutcome')->find($jsonOutcome->id);
                $outcome->setAffinity($jsonOutcome->affinity);
                $em->persist($outcome);

                $jsonAnswer = $jsonOutcome->answer;
                /** @var $answer Answer */
                $answer = $em->getRepository('LaCoreBundle:Answer')->find($jsonAnswer->id);
                $answer->setAnswer($jsonAnswer->answer);
                $em->persist($answer);
            }

        }
        $em->flush();

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
        //return View::create($id, 200);
        $card = new Card($learningEntity);
        return View::create($card, 200);
    }


}
