<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\CoreBundle\Model\Visitor\Goal;

use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\AgoraBase;
use La\CoreBundle\Entity\AgoraGoal;
use La\CoreBundle\Entity\Repository\ActionRepository;
use La\CoreBundle\Entity\Techne;
use La\CoreBundle\Entity\Uplink;
use La\CoreBundle\Entity\User;
use La\CoreBundle\Model\Random\WeightedRandomProvider;
use La\CoreBundle\Visitor\AgoraGoalVisitorInterface;
use La\CoreBundle\Visitor\TechneVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;


/**
 * @DI\Service("la_core.action_provider_for_goal_visitor")
 */
class ActionProviderForGoalVisitor implements
    VisitorInterface,
    AgoraGoalVisitorInterface,
    TechneVisitorInterface
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
     * @var WeightedRandomProvider
     */
    private $weightedRandomProvider;

    /**
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext
     * @param ActionRepository $actionRepository
     * @param WeightedRandomProvider $weightedRandomProvider
     *
     * @DI\InjectParams({
     *  "securityContext" = @DI\Inject("security.context"),
     *  "actionRepository" = @DI\Inject("la_core.repository.action"),
     *  "weightedRandomProvider" = @DI\Inject("la.core_bundle.model.random.weighted_random_provider")
     * })
     */
    public function __construct(SecurityContextInterface $securityContext, ActionRepository $actionRepository, WeightedRandomProvider $weightedRandomProvider)
    {
        $this->securityContext = $securityContext;
        $this->actionRepository = $actionRepository;
        $this->weightedRandomProvider = $weightedRandomProvider;
    }


    /**
     * {@inheritdoc}
     */
    public function visitAgoraGoal(AgoraGoal $goal)
    {
        return $goal->getAgora()->accept($this);
    }

    public function visitTechne(Techne $techne) {
        /* @var $user User */

        $user = $this->securityContext->getToken()->getUser();

        $downLinks = $techne->getDownlinks();
        /* @var Uplink $downLink */
        foreach ($downLinks as $downLink) {
            /** @var AgoraBase $agora */
            $agora = $downLink->getChild();
            $candidateAction = $this->actionRepository->findOneOrNullUnvisitedActionsForAgora($user,$agora);
            if (!is_null($candidateAction)) {
               $this->weightedRandomProvider->add($candidateAction,$downLink->getWeight());
            }
        }
        $selectedLearningEntity = $this->weightedRandomProvider->provide();

        if (!is_null($selectedLearningEntity)) {
            return $selectedLearningEntity;
        }

        if (is_null($selectedLearningEntity)) {
            /* @var Uplink $downLink */
            foreach ($downLinks as $downLink) {
                /** @var AgoraBase $agora */
                $agora = $downLink->getChild();
                $candidateAction = $this->actionRepository->findOneOrNullPostponedActionsForAgora($user,$agora);
                if (!is_null($candidateAction)) {
                    $this->weightedRandomProvider->add($candidateAction,$downLink->getWeight());
                }
            }
            $selectedLearningEntity = $this->weightedRandomProvider->provide();
        }

        return $selectedLearningEntity;

    }

}
