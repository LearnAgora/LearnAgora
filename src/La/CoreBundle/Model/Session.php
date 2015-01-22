<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\CoreBundle\Model;

use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Goal;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @DI\Service("la_core.session")
 */
class Session
{
    /**
     * @var SessionInterface
     *
     */
    private $session;

    /**
     * Constructor.
     *
     * @param SessionInterface $session
     *
     * @DI\InjectParams({
     *  "session" = @DI\Inject("session")
     * })
     */
    public function __construct(SessionInterface $session)
    {
        var_dump($session);
        $this->session = $session;
    }

    public function setGoal(Goal $goal) {
        $this->session->set('goalId', $goal->getId());
        $this->session->set('goalName',$goal->getName());
        $this->session->set('goalAffinity',$goal->getAffinity());
    }

    public function updateGoal() {

    }

    public function clearGoal(Goal $goal = null) {
        $willClearGoal = false;

        if (is_null($goal)) {
            $willClearGoal = true;
        } else {
            //check if the goal is active
            $activeGoalId = $this->session->has('goalId') ? $this->session->get('goalId') : 0;

            if ($goal->getId() == $activeGoalId) {
                $willClearGoal = true;

            }
        }

        if ($willClearGoal) {
            $this->session->remove('goalId');
            $this->session->remove('goalName');
            $this->session->remove('goalAffinity');
        }
    }

}
