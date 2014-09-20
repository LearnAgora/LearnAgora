<?php

namespace La\LearnodexBundle\Model;

use Doctrine\Common\Persistence\ObjectRepository;
use La\LearnodexBundle\Model\Exception\CardNotFoundException;

/**
 * This random card provider naively fetches all the cards from the
 * repository, just to return the one from the whole set.
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
     */
    public function __construct(ObjectRepository $learningEntityRepository)
    {
        $this->learningEntityRepository = $learningEntityRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        $learningEntities = $this->learningEntityRepository->findAll();

        if (count($learningEntities)) {
            shuffle($learningEntities);

            return new Card($learningEntities[0]);
        }

        throw new CardNotFoundException();
    }
}
