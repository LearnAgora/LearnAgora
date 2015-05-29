<?php

namespace La\SecurityBundle\Event\Listener;

use FOS\UserBundle\Event\FormEvent;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @DI\Service
 */
class ManualRegistrationSuccessListener
{

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    private $frontendUrl;

    /**
     * Constructor.
     *
     * @param EventDispatcherInterface $eventDispatcher
     * @param string $frontendUrl
     *
     * @DI\InjectParams({
     *  "eventDispatcher" = @DI\Inject("event_dispatcher"),
     *  "frontendUrl" = @DI\Inject("%frontend_url%")
     * })
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, $frontendUrl)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->frontendUrl = $frontendUrl;
    }

    /**
     * @DI\Observe(FOS\UserBundle\FOSUserEvents::REGISTRATION_SUCCESS)
     *
     * @param FormEvent $formEvent
     */
    public function onResult(FormEvent $formEvent)
    {
        $response = new RedirectResponse('http://'.$this->frontendUrl);
        $formEvent->setResponse($response);
    }
}
