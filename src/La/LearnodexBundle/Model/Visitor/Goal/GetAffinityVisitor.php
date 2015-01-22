<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\LearnodexBundle\Model\Visitor\Goal;

use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Affinity;
use La\CoreBundle\Entity\AgoraGoal;
use La\CoreBundle\Entity\PersonaGoal;
use La\CoreBundle\Entity\Repository\AffinityRepository;
use La\CoreBundle\Entity\Repository\PersonaMatchRepository;
use La\CoreBundle\Visitor\AgoraGoalVisitorInterface;
use La\CoreBundle\Visitor\PersonaGoalVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * @DI\Service("la_core.get_affinity_visitor")
 */

class GetAffinityVisitor implements
    VisitorInterface,
    AgoraGoalVisitorInterface,
    PersonaGoalVisitorInterface
{
    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * @var AffinityRepository
     */
    private $affinityRepository;

    /**
     * @var PersonaMatchRepository
     */
    private $personaMatchRepository;

    /**
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext
     * @param AffinityRepository $affinityRepository
     * @param PersonaMatchRepository $personaMatchRepository
     *
     * @DI\InjectParams({
     *  "securityContext" = @DI\Inject("security.context"),
     *  "affinityRepository" = @DI\Inject("la_core.repository.affinity"),
     *  "personaMatchRepository" = @DI\Inject("la_core.repository.persona_match")
     * })
     */
    public function __construct(SecurityContextInterface $securityContext, AffinityRepository $affinityRepository, PersonaMatchRepository $personaMatchRepository)
    {
        $this->securityContext = $securityContext;
        $this->affinityRepository = $affinityRepository;
        $this->personaMatchRepository = $personaMatchRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function visitAgoraGoal(AgoraGoal $goal)
    {
        $user = $this->securityContext->getToken()->getUser();
        /* @var Affinity $affinity */
        $affinity = $this->affinityRepository->findOneBy(array("user"=>$user,"Agora"=>$goal->getAgora()));
        return $affinity->getValue();
    }

    /**
     * {@inheritdoc}
     */
    public function visitPersonaGoal(PersonaGoal $goal)
    {
        return 65;
    }

}
