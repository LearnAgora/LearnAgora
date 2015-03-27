<?php

namespace La\CoreBundle\Model\Goal;

use Doctrine\Common\Persistence\ObjectRepository;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Affinity;
use La\CoreBundle\Entity\AgoraGoal;
use La\CoreBundle\Entity\Goal;
use La\CoreBundle\Entity\PersonaGoal;
use La\CoreBundle\Entity\PersonaMatch;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * @DI\Service("la_core.goal_manager")
 */
class GoalManager
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * @var ObjectRepository
     */
    private $goalRepository;

    /**
     * @var ObjectRepository
     */
    private $affinityRepository;

    /**
     * @var ObjectRepository
     */
    private $personaMatchRepository;

    /**
     * Constructor.
     *
     * @param SessionInterface $session
     * @param SecurityContextInterface $securityContext
     * @param ObjectRepository $goalRepository
     * @param ObjectRepository $affinityRepository
     * @param ObjectRepository $personaMatchRepository
     *
     * @DI\InjectParams({
     *  "session" = @DI\Inject("session"),
     *  "securityContext" = @DI\Inject("security.context"),
     *  "goalRepository" = @DI\Inject("la_core.repository.goal"),
     *  "affinityRepository" = @DI\Inject("la_core.repository.affinity"),
     *  "personaMatchRepository" = @DI\Inject("la_core.repository.persona_match")
     * })
     */
    public function __construct(SessionInterface $session, SecurityContextInterface $securityContext, ObjectRepository $goalRepository, ObjectRepository $affinityRepository, ObjectRepository $personaMatchRepository)
    {
        $this->session = $session;
        $this->securityContext = $securityContext;
        $this->goalRepository = $goalRepository;
        $this->affinityRepository = $affinityRepository;
        $this->personaMatchRepository = $personaMatchRepository;
    }

    public function setGoal(Goal $goal) {
        $this->session->set('goalId', $goal->getId());
        $this->storeGoalName($goal);
        $this->storeGoalAffinity($goal);
    }

    // TODO: turns out to not be necessary, remove, after introducing (an) event(s) to record changes in affinity and persona matching
    public function updateGoal() {
        if ($activeGoalId = $this->getActiveGoal()) {
            /** @var Goal $goal */
            $goal = $this->goalRepository->find($activeGoalId);

            $this->storeGoalName($goal);
            $this->storeGoalAffinity($goal);
        }
    }

    public function clearGoal(Goal $goal = null) {
        $willClearGoal = false;

        if (is_null($goal) || ($goal->getId() == $this->getActiveGoal())) {
            $willClearGoal = true;
        }

        if ($willClearGoal) {
            $this->session->remove('goalId');
            $this->session->remove('goalName');
            $this->session->remove('goalAffinity');
        }
    }

    private function getActiveGoal() {
        return $this->session->get('goalId', false);
    }

    private function storeGoalName(Goal $goal) {
        $this->session->set('goalName', $goal->getName());
    }

    private function storeGoalAffinity(Goal $goal) {
        $user = $this->securityContext->getToken()->getUser();

        $goalAffinity = 0;

        if ($goal instanceof PersonaGoal) {
            /* @var PersonaMatch $personaMatch */
            $personaMatch = $this->personaMatchRepository->findOneBy(array("user" => $user, "persona" => $goal->getPersona()));
            $goalAffinity = $personaMatch ? 100 - $personaMatch->getDifference() : 0;
        } else if ($goal instanceof AgoraGoal) {
            /* @var Affinity $affinity */
            $affinity = $this->affinityRepository->findOneBy(array("user" => $user, "agora" => $goal->getAgora()));
            $goalAffinity = $affinity->getValue();
        }

        $this->session->set('goalAffinity', $goalAffinity);
    }
}
