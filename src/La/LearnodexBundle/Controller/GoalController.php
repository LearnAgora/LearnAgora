<?php

namespace La\LearnodexBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\AgoraGoal;
use La\CoreBundle\Entity\Goal;
use La\CoreBundle\Entity\LearningEntity;
use La\CoreBundle\Entity\Persona;
use La\CoreBundle\Entity\PersonaGoal;
use La\LearnodexBundle\Model\Visitor\Goal\GetNameVisitor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
     * @var SessionInterface
     *
     * @DI\Inject("la_core.session")
     */
    private $session;

    public function createAgoraGoalAction($id)
    {
        $user = $this->securityContext->getToken()->getUser();

        /** @var $agora Agora */
        if ($id) {
            $agora = $this->agoraRepository->find($id);
        } else {
            throw $this->createNotFoundException( 'No learning entity found for id ' . $id );
        }

        $goal = new AgoraGoal();
        $goal->setUser($user);
        $goal->setAgora($agora);
        $this->entityManager->persist($goal);
        $this->entityManager->flush();

        $this->session->setGoal($goal);
        return $this->redirect($this->generateUrl('card_auto'));
    }
    public function createPersonaGoalAction($id)
    {
        $user = $this->securityContext->getToken()->getUser();

        /** @var $persona Persona */
        if ($id) {
            $persona = $this->personaRepository->find($id);
        } else {
            throw $this->createNotFoundException( 'No persona found for id ' . $id );
        }

        $goal = new PersonaGoal();
        $goal->setUser($user);
        $goal->setPersona($persona);
        $this->entityManager->persist($goal);
        $this->entityManager->flush();

        $this->session->setGoal($goal);
        return $this->redirect($this->generateUrl('card_auto'));
    }

    public function removeGoalAction($id) {
        /** @var $goal Goal */
        if ($id) {
            $goal = $this->goalRepository->find($id);
        } else {
            throw $this->createNotFoundException( 'No goal found for id ' . $id );
        }

        //check if the goal is active
        $activeGoalId = $this->session->has('goalId') ? $this->session->get('goalId') : 0;

        if ($id == $activeGoalId) {
            $this->session->clearGoal();

        }

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
        $this->session->setGoal($goal);
        return $this->redirect($this->generateUrl('card_auto'));
    }

    public function closeAction() {
        $this->session->clearGoal();
        return $this->redirect($this->generateUrl('card_auto'));
    }

}
