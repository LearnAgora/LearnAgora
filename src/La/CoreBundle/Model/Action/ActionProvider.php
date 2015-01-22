<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\CoreBundle\Model\Action;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Goal;
use La\CoreBundle\Entity\Repository\ActionRepository;
use La\CoreBundle\Model\Visitor\Goal\ActionProviderForGoalVisitor;
use Symfony\Component\Security\Core\SecurityContextInterface;
use La\CoreBundle\Entity\Action;
use La\CoreBundle\Entity\Outcome;
use La\CoreBundle\Entity\Trace;
use La\CoreBundle\Entity\Repository\TraceRepository;
use Symfony\Component\Security\Core\User\User;


/**
 * @DI\Service("la_core.action_provider")
 */
class ActionProvider
{
    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * @var ActionRepository
     */
    private $actionRepository;

    /**
     * @var ActionProviderForGoalVisitor
     */
    private $actionProviderForGoalVisitor;

    /**
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext
     * @param ActionRepository $actionRepository
     * @param ActionProviderForGoalVisitor $actionProviderForGoalVisitor
     *
     * @DI\InjectParams({
     *  "securityContext" = @DI\Inject("security.context"),
     *  "actionRepository" = @DI\Inject("la_core.repository.action"),
     *  "actionProviderForGoalVisitor" = @DI\Inject("la_core.action_provider_for_goal_visitor")
     * })
     */
    public function __construct(SecurityContextInterface $securityContext, ActionRepository $actionRepository, ActionProviderForGoalVisitor $actionProviderForGoalVisitor)
    {
        $this->securityContext = $securityContext;
        $this->actionRepository = $actionRepository;
        $this->actionProviderForGoalVisitor = $actionProviderForGoalVisitor;
    }

    public function getRandomAction(Goal $goal = null) {
        /* @var $user User */
        $user = $this->securityContext->getToken()->getUser();

        if (!is_null($goal)) {
            return $this->getRandomActionForGoal($goal);
        }

        /* LearningEntity $selectedLearningEntity */
        $selectedLearningEntity = $this->actionRepository->findOneOrNullUnvisitedActions($user);

        if (is_null($selectedLearningEntity)) {
            $selectedLearningEntity = $this->actionRepository->findOneOrNullPostponedActions($user);
        }
        return $selectedLearningEntity;
    }

    public function getRandomActionForGoal(Goal $goal)
    {
        /* LearningEntity $selectedLearningEntity */
        $selectedLearningEntity = $goal->accept($this->actionProviderForGoalVisitor);

        return $selectedLearningEntity;
    }

}
