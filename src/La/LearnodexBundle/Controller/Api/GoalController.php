<?php

namespace La\LearnodexBundle\Controller\Api;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use FOS\RestBundle\View\View;
use Hateoas\Configuration\Route;
use Hateoas\Representation\Factory\PagerfantaFactory;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\AgoraGoal;
use La\CoreBundle\Entity\Goal;
use La\CoreBundle\Entity\Persona;
use La\CoreBundle\Entity\PersonaGoal;
use La\CoreBundle\Entity\User;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GoalController extends Controller
{
    /**
     * @var ObjectRepository
     *
     * @DI\Inject("la_core.repository.user"),
     */
    private $userRepository;

    /**
     * @var ObjectManager $entityManager
     *
     *  @DI\Inject("doctrine.orm.entity_manager"),
     */
    private $entityManager;
    /**
     * @var ObjectRepository
     *
     * @DI\Inject("la_core.repository.agora")
     */
    private $agoraRepository;
    /**
     * @var ObjectRepository
     *
     * @DI\Inject("la_core.repository.persona")
     */
    private $personaRepository;

    /**
     * @var ObjectRepository
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
     * @var ObjectRepository
     *
     * @DI\Inject("la_core.repository.persona_goal")
     */
    private $personaGoalRepository;

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
    public function loadAllAction(Request $request)
    {
        /** @var User $user */
        if (null === ($user = $this->userRepository->find(1))) {
            throw new NotFoundHttpException('User could not be found.');
        }

        // sets up the generic pagination
        $pager = new Pagerfanta(new ArrayAdapter($this->goalRepository->findBy(array("user"=>$user))));

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
        /** @var User $user */
        if (null === ($user = $this->userRepository->find(1))) {
            throw new NotFoundHttpException('User could not be found.');
        }

        /** @var $agora Agora */
        if (null === ($agora = $this->agoraRepository->find($id))) {
            throw new NotFoundHttpException('Agora could not be found.');
        }

        $goal = $this->agoraGoalRepository->findOneBy(array("user"=>$user,"agora"=>$agora));

        if (is_null($goal)) {
            $goal = new AgoraGoal();
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
     * @throws NotFoundHttpException if the Persona cannot be found
     *
     * @Doc\ApiDoc(
     *  section="Learnodex",
     *  description="Creates a goal for a Persona and returns the goal",
     *  statusCodes={
     *      200="created goal returned when successful",
     *      404="Returned when no persona is found",
     *  })
     */

    public function createPersonaGoalAction($id)
    {
        /** @var User $user */
        if (null === ($user = $this->userRepository->find(1))) {
            throw new NotFoundHttpException('User could not be found.');
        }
        /** @var $persona Persona */
        if (null === ($persona = $this->personaRepository->find($id))) {
            throw new NotFoundHttpException('Persona could not be found.');
        }

        $goal = $this->personaGoalRepository->findOneBy(array("user"=>$user,"persona"=>$persona));

        if (is_null($goal)) {
            $goal = new PersonaGoal();
            $goal->setUser($user);
            $goal->setPersona($persona);
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
    public function activateAction($id) {
        /** @var $goal Goal */
        if (null === ($goal = $this->goalRepository->find($id))) {
            throw new NotFoundHttpException('Goal could not be found.');
        }

        $goal->setActive(true);
        $this->entityManager->persist($goal);
        $this->entityManager->flush();

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
     *  description="deactivates a goal",
     *  statusCodes={
     *      200="created goal returned when activated",
     *      404="Returned when no goal is found",
     *  })
     */
    public function deActivateAction($id) {
        /** @var $goal Goal */
        if (null === ($goal = $this->goalRepository->find($id))) {
            throw new NotFoundHttpException('Goal could not be found.');
        }

        $goal->setActive(false);
        $this->entityManager->persist($goal);
        $this->entityManager->flush();

        return View::create($goal, 200);
    }


}
