<?php

namespace La\CoreBundle\Event\Listener;

use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @DI\Service
 */
class RegistrationSuccessListener
{

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var ContainerInterface
     */
    private $container;


    /**
     * Constructor.
     *
     * @param EventDispatcherInterface $eventDispatcher
     * @param ContainerInterface $container
     *
     * @DI\InjectParams({
     *  "eventDispatcher" = @DI\Inject("event_dispatcher"),
     *  "container" = @DI\Inject("service_container")
     * })
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, ContainerInterface $container)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->container = $container;
    }

    /**
     * @DI\Observe(FOSUserEvents::REGISTRATION_SUCCESS)
     *
     * @param FormEvent $formEvent
     */
    public function onResult(FormEvent $formEvent)
    {
        $response = new RedirectResponse('http://'.$this->container->getParameter('frontend_url'));
        $formEvent->setResponse($response);
    }
}
