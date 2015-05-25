<?php

namespace La\SecurityBundle\Event\Listener;

use FOS\UserBundle\Event\FormEvent;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @DI\Service
 */
class RegistrationSuccessListener
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @DI\InjectParams({
     *     "session" = @DI\Inject("session")
     * })
     *
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @DI\Observe(FOS\UserBundle\FOSUserEvents::REGISTRATION_SUCCESS)
     *
     * @param FormEvent $event
     */
    public function onNewRegistration(FormEvent $event)
    {
        $targetPath = $this->session->get('_security.main.target_path');

        if (null !== $targetPath) {
            $event->setResponse(new RedirectResponse($targetPath));
        }
    }
}
