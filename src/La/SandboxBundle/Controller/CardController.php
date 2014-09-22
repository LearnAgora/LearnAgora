<?php

namespace La\SandboxBundle\Controller;

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
     *     "randomCardProvider" = @DI\Inject("naive.random.card.provider")
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
    public function randomAction()
    {
        try {
            $card = $this->randomCardProvider->getCard();
        } catch (CardNotFoundException $e) {
            throw new NotFoundHttpException(null, $e);
        }

        return $card;
    }
}
