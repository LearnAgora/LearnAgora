<?php

namespace La\LearnodexBundle\Controller\Api;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use FOS\RestBundle\View\View;
use Hateoas\Configuration\Route;
use Hateoas\Representation\Factory\PagerfantaFactory;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\AgoraBase;
use La\CoreBundle\Entity\AgoraGoal;
use La\CoreBundle\Entity\Goal;
use La\CoreBundle\Entity\Repository\GoalRepository;
use La\CoreBundle\Entity\User;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\SecurityContextInterface;

class GoalController extends Controller
{
    /**
     * @var SecurityContextInterface
     *
     * @DI\Inject("security.context")
     */
    private $securityContext;

    /**
     * @var ObjectManager $entityManager
     *
     *  @DI\Inject("doctrine.orm.entity_manager"),
     */
    private $entityManager;
    /**
     * @var ObjectRepository
     *
     * @DI\Inject("la_core.repository.agora_base")
     */
    private $agoraBaseRepository;

    /**
     * @var GoalRepository
     *
     * @DI\Inject("la_core.repository.goal")
     */
    private $goalRepository;

    /**
     * @var ObjectRepository
     *
     * @DI\Inject("la_core.repository.agora_goal")
     */
    private $agoraGoalRepository;

    /**
     * @param Request $request
     *
     * @return View
     *
     * @todo find a way to ignore pagination parameters entirely
     *
     * @Doc\ApiDoc(
     *  section="Learnodex",
     *  description="Returns all goals for a user",
     *  statusCodes={
     *      200="The collection of goals when successful",
     *  })
     */
    public function loadAllAction(Request $request, $id=0)
    {
        /** @var User $user */
        $user = $this->securityContext->getToken()->getUser();
        if ($id) {
            $user = $this->entityManager->getRepository('LaCoreBundle:User')->find($id);
        }

        // sets up the generic pagination
        $pager = new Pagerfanta(new ArrayAdapter($this->goalRepository->findBy(array("user"=>$user))));

        // this handles the HATEOAS part of same pagination in the next call
        $factory = new PagerfantaFactory();

        return View::create($factory->createRepresentation($pager, new Route($request->get('_route'),array('id'=>$id))), 200);
    }

    /**
     * @param Request $request
     *
     * @return View
     *
     * @todo find a way to ignore pagination parameters entirely
     *
     * @Doc\ApiDoc(
     *  section="Learnodex",
     *  description="Returns all active goals for a user",
     *  statusCodes={
     *      200="The collection of goals when successful",
     *  })
     */
    public function loadActiveAction(Request $request)
    {
        /** @var $user User */
        $user = $this->securityContext->getToken()->getUser();

        // sets up the generic pagination
        $pager = new Pagerfanta(new ArrayAdapter($this->goalRepository->findBy(array("user"=>$user, "active"=>1))));

        // this handles the HATEOAS part of same pagination in the next call
        $factory = new PagerfantaFactory();

        return View::create($factory->createRepresentation($pager, new Route($request->get('_route'))), 200);
    }

    /**
     * @param int $id
     *
     * @return View
     *
     * @throws NotFoundHttpException if the agora cannot be found
     *
     * @Doc\ApiDoc(
     *  section="Learnodex",
     *  description="Creates a goal for an Agora and returns the goal",
     *  statusCodes={
     *      200="created goal returned when successful",
     *      404="Returned when no agora is found",
     *  })
     */
    public function createAgoraGoalAction($id)
    {
        /** @var $user User */
        $user = $this->securityContext->getToken()->getUser();

        /** @var $agora AgoraBase */
        if (null === ($agora = $this->agoraBaseRepository->find($id))) {
            throw new NotFoundHttpException('Agora could not be found.');
        }

        $goal = $this->agoraGoalRepository->findOneBy(array("user"=>$user,"agora"=>$agora));

        if (is_null($goal)) {
            $goal = new AgoraGoal();
            $goal->setActive(false);
            $goal->setUser($user);
            $goal->setAgora($agora);
            $this->entityManager->persist($goal);
            $this->entityManager->flush();
        }

        return View::create($goal, 200);
    }

    /**
     * @param int $id
     *
     * @return View
     *
     * @throws NotFoundHttpException if the Goal cannot be found
     *
     * @Doc\ApiDoc(
     *  section="Learnodex",
     *  description="Removes a goal",
     *  statusCodes={
     *      204="No content returned when successful",
     *      404="Returned when no goal is found",
     *  })
     */
    public function removeAction($id) {
        /** @var $goal Goal */
        if (null === ($goal = $this->goalRepository->find($id))) {
            throw new NotFoundHttpException('Goal could not be found.');
        }

        $this->entityManager->remove($goal);
        $this->entityManager->flush();

        return View::create(null, 204);
    }


    /**
     * @param int $id
     * @param bool $activeFlag
     *
     * @return View
     *
     * @throws NotFoundHttpException if the Goal cannot be found
     *
     * @Doc\ApiDoc(
     *  section="Learnodex",
     *  description="Activates a goal",
     *  statusCodes={
     *      200="created goal returned when activated",
     *      404="Returned when no goal is found",
     *  })
     */
    public function activateAction($id, $activeFlag) {
        /** @var $goal Goal */
        if (null === ($goal = $this->goalRepository->find($id))) {
            throw new NotFoundHttpException('Goal could not be found.');
        }

        $this->goalRepository->resetActiveGoalsFor($goal->getUser());

        $goal->setActive($activeFlag);
        $this->entityManager->persist($goal);
        $this->entityManager->flush();

        return View::create($goal, 200);
    }
}
