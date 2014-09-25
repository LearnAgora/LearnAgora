<?php

namespace La\LearnodexBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
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
     * Constructor.
     *
     * @param RandomCardProviderInterface $randomCardProvider
     *
     * @DI\InjectParams({
     *     "randomCardProvider" = @DI\Inject("random_card_provider")
     * })
     */
    public function __construct(RandomCardProviderInterface $randomCardProvider)
    {
        $this->randomCardProvider = $randomCardProvider;
    }

    /**
     * @Rest\View
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

        return $card;
    }
}
