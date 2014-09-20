<?php

namespace La\SandboxBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use La\LearnodexBundle\Model\RandomCardProviderInterface;

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
     */
    public function randomAction()
    {
        return $this->randomCardProvider->get();
    }
}
