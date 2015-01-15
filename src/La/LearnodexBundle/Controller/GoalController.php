<?php

namespace La\LearnodexBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Goal;
use La\CoreBundle\Entity\Persona;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
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
     * @DI\Inject("la_core.repository.persona")
     */
    private $personaRepository;
    /**
     * @var ObjectRepository
     *
     * @DI\Inject("la_core.repository.goal")
     */
    private $goalRepository;

    public function createPersonaAction($id)
    {
        $user = $this->securityContext->getToken()->getUser();

        /** @var $persona Persona */
        if ($id) {
            $persona = $this->personaRepository->find($id);
        } else {
            throw $this->createNotFoundException( 'No persona found for id ' . $id );
        }

        $goal = new Goal();
        $goal->setUser($user);
        $goal->setPersona($persona);
        $this->entityManager->persist($goal);
        $this->entityManager->flush();

        $session = new Session();
        //$session->start();
        $session->set('goal', $goal);
        return $this->redirect($this->generateUrl('card_auto'));
    }

    public function openAction($id) {
        /** @var $goal Goal */
        if ($id) {
            $goal = $this->goalRepository->find($id);
        } else {
            throw $this->createNotFoundException( 'No goal found for id ' . $id );
        }
        $session = new Session();
        $session->set('goal',$goal);
        die(print_r($goal->getPersona()->getUser()->getUsername()));
        return $this->redirect($this->generateUrl('card_auto'));
    }

    public function closeAction() {
        $session = new Session();
        $session->remove('goal');
        return $this->redirect($this->generateUrl('card_auto'));
    }

}
