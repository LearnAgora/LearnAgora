<?php

namespace La\LearnodexBundle\Model;

use Doctrine\Common\Persistence\ObjectRepository;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Outcome;
use La\CoreBundle\Entity\Result;
use La\CoreBundle\Entity\Trace;
use La\LearnodexBundle\Model\Exception\CardNotFoundException;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * @DI\Service("la_learnodex.simple_random_card_provider")
 */
class SimpleRandomCardProvider implements RandomCardProviderInterface
{
    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * @var ObjectRepository
     */
    private $learningEntityRepository;

    /**
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext
     * @param ObjectRepository $learningEntityRepository
     *
     * @DI\InjectParams({
     *  "securityContext" = @DI\Inject("security.context"),
     *  "learningEntityRepository" = @DI\Inject("la_core.repository.action")
     * })
     */
    public function __construct(SecurityContextInterface $securityContext, ObjectRepository $learningEntityRepository)
    {
        $this->securityContext = $securityContext;
        $this->learningEntityRepository = $learningEntityRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getCard()
    {
        $selectedLearningEntity = $this->learningEntityRepository->findOneOrNullUnvisitedActions($this->securityContext->getToken()->getUser());

        if (is_null($selectedLearningEntity)) {
            $selectedLearningEntity = $this->learningEntityRepository->findOneOrNullPostponedActions($this->securityContext->getToken()->getUser());
        }

        if (!is_null($selectedLearningEntity)) {
            return new Card($selectedLearningEntity);
        }

        throw new CardNotFoundException();

    }
}
