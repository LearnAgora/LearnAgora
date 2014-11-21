<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\CoreBundle\Model\Action;

use Doctrine\Common\Persistence\ObjectRepository;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Security\Core\SecurityContextInterface;
use La\CoreBundle\Entity\Action;
use La\CoreBundle\Entity\Outcome;
use La\CoreBundle\Entity\Trace;
use La\CoreBundle\Entity\Repository\TraceRepository;



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
     * @var ObjectRepository
     */
    private $actionRepository;

    /**
     * @var TraceRepository
     */
    private $traceRepository;

    /**
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext
     * @param ObjectRepository $actionRepository
     * @param TraceRepository $traceRepository
     *
     * @DI\InjectParams({
     *  "securityContext" = @DI\Inject("security.context"),
     *  "actionRepository" = @DI\Inject("la_core.repository.action"),
     *  "traceRepository" = @DI\Inject("la_core.repository.trace")
     * })
     */
    public function __construct(SecurityContextInterface $securityContext, ObjectRepository $actionRepository, TraceRepository $traceRepository)
    {
        $this->securityContext = $securityContext;
        $this->actionRepository = $actionRepository;
        $this->traceRepository = $traceRepository;
    }

    public function findOneOrNullUnvisitedActions()
    {
        $user = $this->securityContext->getToken()->getUser();
        $unvisitedActions = array();

        $actions = $this->actionRepository->findAll();

        if (count($actions) == 0)
        {
            return null;
        }

        shuffle($actions);

        /* @var Action $action */
        foreach ($actions as $action)
        {
            $traces = $this->traceRepository->findAllForLearningEntity($action,$user);
            if (count($traces) == 0)
            {
                $unvisitedActions[] = $action;
            }
        }

        if (count($unvisitedActions) == 0)
        {
            return null;
        }

        return $unvisitedActions[0];
    }

    public function findOneOrNullPostponedActions()
    {
        $user = $this->securityContext->getToken()->getUser();
        $postponedActions = array();

        $actions = $this->actionRepository->findAll();

        if (count($actions) == 0)
        {
            return null;
        }

        /* @var Action $action */
        foreach ($actions as $action)
        {
            /* @var Trace $trace */
            $trace = $this->traceRepository->findLastForLearningEntity($action,$user);
            if ($trace && is_a($trace->getOutcome(),'La\CoreBundle\Entity\ButtonOutcome') && $trace->getOutcome()->getCaption() == 'LATER')
            {
                $postponedActions[] = $action;
            }
        }

        if (count($postponedActions) == 0) {
            return null;
        }

        return $postponedActions[0];
    }

}
