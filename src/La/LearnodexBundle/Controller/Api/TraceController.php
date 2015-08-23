<?php

namespace La\LearnodexBundle\Controller\Api;

use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Outcome;
use La\CoreBundle\Entity\Trace;
use La\CoreBundle\Entity\User;
use La\CoreBundle\Event\TraceEvent;
use La\CoreBundle\Events;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\SecurityContextInterface;

class TraceController
{
    /**
     * @var SecurityContextInterface
     */
    private $securityContext;
    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * @var ObjectRepository
     */
    private $userRepository;

    /**
     * @var ObjectRepository
     */
    private $outcomeRepository;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext
     * @param ObjectManager $entityManager
     * @param ObjectRepository $userRepository
     * @param ObjectRepository $outcomeRepository
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @DI\InjectParams({
     *     "securityContext" = @DI\Inject("security.context"),
     *     "entityManager" = @DI\Inject("doctrine.orm.entity_manager"),
     *     "userRepository" = @DI\Inject("la_core.repository.user"),
     *     "outcomeRepository" = @DI\Inject("la_core.repository.outcome"),
     *     "eventDispatcher" = @DI\Inject("event_dispatcher")
     * })
     */
    public function __construct(SecurityContextInterface $securityContext, ObjectManager $entityManager, ObjectRepository $userRepository, ObjectRepository $outcomeRepository, EventDispatcherInterface $eventDispatcher)
    {
        $this->securityContext = $securityContext;
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->outcomeRepository = $outcomeRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param int $outcomeId
     *
     * @return View
     *
     * @throws NotFoundHttpException if the user or outcome cannot be found
     *
     * @Doc\ApiDoc(
     *  section="Learnodex",
     *  description="Records a trace",
     *  statusCodes={
     *      204="No content returned when successful",
     *      404="Returned when no user or outcome is found",
     *  })
     */
    public function traceAction($outcomeId)
    {
        /** @var $user User */
        $user = $this->securityContext->getToken()->getUser();

        /** @var Outcome $outcome */
        if (null === ($outcome = $this->outcomeRepository->find($outcomeId))) {
            throw new NotFoundHttpException('Outcome could not be found.');
        }

        $trace = new Trace();
        $trace->setUser($user);
        $trace->setOutcome($outcome);
        $trace->setCreatedTime(new DateTime(date('Y-m-d H:i:s', time())));
        $this->entityManager->persist($trace);
        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(Events::TRACE_CREATED, new TraceEvent($trace));

        $events = $user->getEvents();
        if (count($events) == 0) {
            return View::create(null, 204);
        } else {
            return View::create(['events'=>$events], 200);
        }
    }
}
