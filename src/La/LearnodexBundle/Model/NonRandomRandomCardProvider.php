<?php

namespace La\LearnodexBundle\Model;

use Doctrine\Common\Persistence\ObjectRepository;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\LearningEntity;
use La\LearnodexBundle\Model\Exception\CardNotFoundException;

/**
 * This random card provider is stupid and always fetches the only card it
 * knows.
 *
 * @DI\Service("non_random.random.card.provider")
 */
class NonRandomRandomCardProvider implements RandomCardProviderInterface
{
    /**
     * @var ObjectRepository
     */
    private $learningEntityRepository;

    /**
     * @var int
     */
    private $learningEntityId;

    /**
     * Constructor.
     *
     * @param ObjectRepository $learningEntityRepository
     * @param int $learningEntityId
     *
     * @DI\InjectParams({
     *     "learningEntityRepository" = @DI\Inject("la_core.repository.action"),
     *     "learningEntityRepository" = @DI\Inject("%la_learnodex.non_random_card_id%")
     * })
     */
    public function __construct(ObjectRepository $learningEntityRepository, $learningEntityId)
    {
        $this->learningEntityRepository = $learningEntityRepository;
        $this->learningEntityId = $learningEntityId;
    }

    /**
     * {@inheritdoc}
     */
    public function getCard()
    {
        /** @var LearningEntity $learningEntity */
        $learningEntity = $this->learningEntityRepository->find($this->learningEntityId);

        if (null !== $learningEntity) {
            return new Card($learningEntity);
        }

        throw new CardNotFoundException();
    }
}
