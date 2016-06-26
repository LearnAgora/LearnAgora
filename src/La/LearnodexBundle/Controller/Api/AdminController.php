<?php

namespace La\LearnodexBundle\Controller\Api;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Action;
use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\Domain;
use La\CoreBundle\Entity\LearningContent;
use La\CoreBundle\Entity\LearningEntity;
use La\CoreBundle\Entity\Objective;
use La\CoreBundle\Entity\Outcome;
use La\CoreBundle\Entity\SimpleUrlQuestion;
use La\CoreBundle\Entity\Techne;
use La\CoreBundle\Entity\Uplink;
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

        $card = new Card($learningEntity);
        return View::create($card, 200);
    }

    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();
        /** @var $learningEntity LearningEntity */
        $learningEntity = $em->getRepository('LaCoreBundle:LearningEntity')->find($id);
        foreach ($learningEntity->getUplinks() as $uplink) {
            $em->remove($uplink);
        }
        foreach ($learningEntity->getDownlinks() as $downlink) {
            $em->remove($downlink);
        }
        foreach ($learningEntity->getUserProbabilities() as $userProbability) {
            $em->remove($userProbability);
        }
        /** @var Outcome $outcome */
        foreach ($learningEntity->getOutcomes() as $outcome) {
            foreach ($outcome->getProbabilities() as $outcomeProbability) {
                $em->remove($outcomeProbability);
            }
            foreach ($outcome->getTraces() as $trace) {
                $em->remove($trace);
            }
            $em->remove($outcome);
        }
        $content = $learningEntity->getContent();
        if (is_a($content,'La\CoreBundle\Entity\SimpleUrlQuestion')) {
            /** @var SimpleUrlQuestion $content */
            foreach ($content->getAnswers() as $answer) {
                $em->remove($answer);
            }
        }
        if (is_a($content,'La\CoreBundle\Entity\LearningContent')) {
            /** @var LearningContent $content */
            foreach ($content->getLoms() as $lom) {
                $em->remove($lom);
            }
        }
        $em->remove($content);


        $em->remove($learningEntity);
        $em->flush();
        return View::create(null, 200);
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
            case "objective":
                $learningEntity = new Objective();
                break;
            case "agora":
                $learningEntity = new Agora();
                break;
            case "techne":
                $learningEntity = new Techne();
                break;
            case "domain":
                $learningEntity = new Domain();
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

    public function uplinkAction($childId, $parentId, $weight = 0) {
        $em = $this->getDoctrine()->getManager();

        /** @var $childEntity LearningEntity */
        $childEntity = $em->getRepository('LaCoreBundle:LearningEntity')->find($childId);
        /** @var $parentEntity LearningEntity */
        $parentEntity = $em->getRepository('LaCoreBundle:LearningEntity')->find($parentId);

        $upLink = new Uplink();
        $upLink->setParent($parentEntity);
        $upLink->setChild($childEntity);
        $upLink->setWeight($weight);

        $em->persist($upLink);
        $em->flush();

        $card = new Card($parentEntity);
        return View::create($card, 200);
    }
    public function unlinkAction($parentId, $downlink) {
        $em = $this->getDoctrine()->getManager();

        /** @var $uplink Uplink */
        $upLink = $em->getRepository('LaCoreBundle:Uplink')->find($downlink);
        /** @var $parentEntity LearningEntity */
        $parentEntity = $em->getRepository('LaCoreBundle:LearningEntity')->find($parentId);

        $em->remove($upLink);
        $em->flush();

        $card = new Card($parentEntity);
        return View::create($card, 200);
    }

    public function unlinkChildAction($parentId, $childId) {
        $em = $this->getDoctrine()->getManager();
        /** @var LearningEntity $parentEntity */
        $parentEntity = $em->getRepository('LaCoreBundle:LearningEntity')->find($parentId);
        /** @var LearningEntity $childEntity */
        $childEntity = $em->getRepository('LaCoreBundle:LearningEntity')->find($childId);
        /** @var $uplink Uplink */
        $upLink = $em->getRepository('LaCoreBundle:Uplink')->findOneBy(array('parent'=>$parentEntity, 'child' => $childEntity));

        $em->remove($upLink);
        $em->flush();

        $card = new Card($parentEntity);
        return View::create($upLink, 200);
    }

    public function updateUplinkAction($id,$weight) {
        $em = $this->getDoctrine()->getManager();

        /** @var $uplink Uplink */
        $uplink = $em->getRepository('LaCoreBundle:Uplink')->find($id);
        $uplink->setWeight($weight);

        $em->persist($uplink);
        $em->flush();

        return View::create($uplink, 200);
    }

    public function entityStatisticsAction($id) {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('count', 'count');
        $sql = "SELECT o.id, (select count(*) from Trace t where t.outcome_id=o.id) as count  FROM Outcome o WHERE o.learning_entity_id=?";
        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $id);
        $results = $query->getResult();
        return View::create(['outcomes'=>$results], 200);
    }

}
