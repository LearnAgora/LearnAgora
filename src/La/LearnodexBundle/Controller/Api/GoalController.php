<?php

namespace La\LearnodexBundle\Controller\Api;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\AgoraGoal;
use La\CoreBundle\Entity\Goal;
use La\CoreBundle\Entity\Persona;
use La\CoreBundle\Entity\PersonaGoal;
use La\CoreBundle\Entity\User;
use La\CoreBundle\Model\Goal\GoalManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Nelmio\ApiDocBundle\Annotation as Doc;
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
     * @var GoalManager
     *
     * @DI\Inject("la_core.goal_manager")
     */
    private $goalManager;

    /**
     *
     * @return View
     *
     * @throws NotFoundHttpException if the agora cannot be found
     *
     * @Doc\ApiDoc(
     *  section="Learnodex",
     *  description="Returns all goals for a user",
     *  statusCodes={
     *      200="The collection of goals when successful",
     *  })
     */
    public function loadAllAction()
    {
        /** @var User $user */
        if (null === ($user = $this->userRepository->find(1))) {
            throw new NotFoundHttpException('User could not be found.');
        }

        $goals = $this->goalRepository->findBy(array("user"=>$user));

        return View::create($goals, 200);
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

//        $this->goalManager->setGoal($goal);

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

        //$this->goalManager->setGoal($goal);

        return View::create($goal, 200);
    }

    public function removeGoalAction($id) {
        /** @var $goal Goal */
        if ($id) {
            $goal = $this->goalRepository->find($id);
        } else {
            throw $this->createNotFoundException( 'No goal found for id ' . $id );
        }

        $this->goalManager->clearGoal($goal);

        $this->entityManager->remove($goal);
        $this->entityManager->flush();

        return $this->redirect($this->generateUrl('card_auto'));
    }

    public function openAction($id) {
        /** @var $goal Goal */
        if ($id) {
            $goal = $this->goalRepository->find($id);
        } else {
            throw $this->createNotFoundException( 'No goal found for id ' . $id );
        }
        $this->goalManager->setGoal($goal);
        return $this->redirect($this->generateUrl('card_auto'));
    }

    public function closeAction() {
        $this->goalManager->clearGoal();
        return $this->redirect($this->generateUrl('card_auto'));
    }

}
