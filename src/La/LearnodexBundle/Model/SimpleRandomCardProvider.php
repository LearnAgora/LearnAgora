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
     * @var ObjectRepository
     */
    private $actionRepository;

    /**
     * Constructor.
     *
     * @param ObjectRepository $actionRepository
     *
     * @DI\InjectParams({
     *  "actionRepository" = @DI\Inject("la_core.repository.action")
     * })
     */
    public function __construct(ObjectRepository $actionRepository)
    {
        $this->actionRepository = $actionRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getCard()
    {
        $selectedLearningEntity = $this->actionRepository->findOneOrNullUnvisitedActions();

        if (is_null($selectedLearningEntity)) {
            $selectedLearningEntity = $this->actionRepository->findOneOrNullPostponedActions();
        }

        if (!is_null($selectedLearningEntity)) {
            return new Card($selectedLearningEntity);
        }

        throw new CardNotFoundException();

    }
}
