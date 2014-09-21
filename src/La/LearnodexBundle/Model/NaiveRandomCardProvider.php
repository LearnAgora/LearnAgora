<?php

namespace La\LearnodexBundle\Model;

use Doctrine\Common\Persistence\ObjectRepository;
use JMS\DiExtraBundle\Annotation as DI;
use La\LearnodexBundle\Model\Exception\CardNotFoundException;

/**
 * This random card provider naively fetches all the cards from the
 * repository, just to return the one from the whole set.
 *
 * @DI\Service("naive.random.card.provider")
 */
class NaiveRandomCardProvider implements RandomCardProviderInterface
{
    /**
     * @var ObjectRepository
     */
    private $learningEntityRepository;

    /**
     * Constructor.
     *
     * @param ObjectRepository $learningEntityRepository
     *
     * @DI\InjectParams({
     *     "learningEntityRepository" = @DI\Inject("la_core.repository.action")
     * })
     */
    public function __construct(ObjectRepository $learningEntityRepository)
    {
        $this->learningEntityRepository = $learningEntityRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getCard()
    {
        $learningEntities = $this->learningEntityRepository->findAll();

        if (count($learningEntities)) {
            shuffle($learningEntities);

            return new Card($learningEntities[0]);
        }

        throw new CardNotFoundException();
    }
}
