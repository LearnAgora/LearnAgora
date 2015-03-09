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
use La\LearnodexBundle\Event\TraceEvent;
use La\LearnodexBundle\Events;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TraceController
{
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
     * @param ObjectManager $entityManager
     * @param ObjectRepository $userRepository
     * @param ObjectRepository $outcomeRepository
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @DI\InjectParams({
     *     "entityManager" = @DI\Inject("doctrine.orm.entity_manager"),
     *     "userRepository" = @DI\Inject("la_core.repository.user"),
     *     "outcomeRepository" = @DI\Inject("la_core.repository.outcome"),
     *     "eventDispatcher" = @DI\Inject("event_dispatcher")
     * })
     */
    public function __construct(ObjectManager $entityManager, ObjectRepository $userRepository, ObjectRepository $outcomeRepository, EventDispatcherInterface $eventDispatcher)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->outcomeRepository = $outcomeRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param int $userId
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
    public function traceAction($userId, $outcomeId)
    {
        /** @var User $user */
        if (null === ($user = $this->userRepository->find($userId))) {
            throw new NotFoundHttpException('User could not be found.');
        }

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

        return View::create(null, 204);
    }
}
