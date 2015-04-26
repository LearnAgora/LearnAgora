<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\CoreBundle\Model\Visitor\Goal;

use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\AgoraGoal;
use La\CoreBundle\Entity\Repository\ActionRepository;
use La\CoreBundle\Entity\User;
use La\CoreBundle\Visitor\AgoraGoalVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;


/**
 * @DI\Service("la_core.action_provider_for_goal_visitor")
 */
class ActionProviderForGoalVisitor implements
    VisitorInterface,

    AgoraGoalVisitorInterface
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
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext
     * @param ActionRepository $actionRepository
     *
     * @DI\InjectParams({
     *  "securityContext" = @DI\Inject("security.context"),
     *  "actionRepository" = @DI\Inject("la_core.repository.action"),
     * })
     */
    public function __construct(SecurityContextInterface $securityContext, ActionRepository $actionRepository)
    {
        $this->securityContext = $securityContext;
        $this->actionRepository = $actionRepository;
    }


    /**
     * {@inheritdoc}
     */
    public function visitAgoraGoal(AgoraGoal $goal)
    {
        /* @var $user User */
        $user = $this->securityContext->getToken()->getUser();

        $selectedLearningEntity = $this->actionRepository->findOneOrNullUnvisitedActionsForAgora($user,$goal->getAgora());

        if (is_null($selectedLearningEntity)) {
            $selectedLearningEntity = $this->actionRepository->findOneOrNullPostponedActionsForAgora($user,$goal->getAgora());
        }

        return $selectedLearningEntity;
    }

}
