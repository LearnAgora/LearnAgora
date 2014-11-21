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
     * @var ObjectRepository
     */
    private $traceRepository;

    /**
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext
     * @param ObjectRepository $actionRepository
     * @param ObjectRepository $traceRepository
     *
     * @DI\InjectParams({
     *  "securityContext" = @DI\Inject("security.context"),
     *  "actionRepository" = @DI\Inject("la_core.repository.action"),
     *  "traceRepository" = @DI\Inject("la_core.repository.trace")
     * })
     */
    public function __construct(SecurityContextInterface $securityContext, ObjectRepository $actionRepository, ObjectRepository $traceRepository)
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
            $actionHasTrace = false;

            /** @var $outcome Outcome */
            foreach ($action->getOutcomes() as $outcome)
            {
                $trace = $this->traceRepository->findOneBy( array('user' => $user,'outcome' => $outcome)   );
                if ($trace)
                {
                    $actionHasTrace = true;
                }
            }

            if (!$actionHasTrace)
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
            $actionIsPostponed = false;

            /** @var $outcome Outcome */
            foreach ($action->getOutcomes() as $outcome)
            {
                $trace = $this->traceRepository->findOneBy( array('user' => $user,'outcome' => $outcome)   );
                if ($trace && is_a($trace->getOutcome(),'La\CoreBundle\Entity\ButtonOutcome'))
                {
                    if ($trace->getOutcome()->getCaption() == 'LATER')
                    {
                        $actionIsPostponed = true;
                    }
                }
            }

            if ($actionIsPostponed)
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
