<?php

namespace La\LearnodexBundle\Controller\Api;

use Doctrine\Common\Persistence\ObjectRepository;
use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\LearningEntity;
use La\LearnodexBundle\Model\Card;
use La\LearnodexBundle\Model\Exception\CardNotFoundException;
use La\LearnodexBundle\Model\RandomCardProviderInterface;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CardController
{
    /**
     * @var RandomCardProviderInterface
     */
    private $randomCardProvider;

    /**
     * @var ObjectRepository
     */
    private $learningEntityRepository;

    /**
     * Constructor.
     *
     * @param RandomCardProviderInterface $randomCardProvider
     * @param ObjectRepository $learningEntityRepository
     *
     * @DI\InjectParams({
     *     "randomCardProvider" = @DI\Inject("random_card_provider"),
     *     "learningEntityRepository" = @DI\Inject("la_core.repository.learning_entity")
     * })
     */
    public function __construct(RandomCardProviderInterface $randomCardProvider, ObjectRepository $learningEntityRepository)
    {
        $this->randomCardProvider = $randomCardProvider;
        $this->learningEntityRepository = $learningEntityRepository;
    }

    /**
     * @return View
     *
     * @throws NotFoundHttpException if a random card cannot be found
     *
     * @Doc\ApiDoc(
     *  section="Learnodex",
     *  description="Retrieves a random card",
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when no card is found",
     *  })
     */
    public function randomCardAction()
    {
        try {
            $card = $this->randomCardProvider->getCard();
        } catch (CardNotFoundException $e) {
            throw new NotFoundHttpException('No random card could be found.', $e);
        }

        return View::create($card, 200);
    }

    public function cardAction($cardId)
    {
        $learningEntity = $this->learningEntityRepository->find($cardId);
        if (!$learningEntity)
            throw new NotFoundHttpException('Card could not be found.');

        /** @var LearningEntity $learningEntity */
        $card = new Card($learningEntity);
        return View::create($card, 200);
    }
}
