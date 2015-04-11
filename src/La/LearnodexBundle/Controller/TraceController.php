<?php

namespace La\LearnodexBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\ButtonOutcome;
use La\CoreBundle\Entity\LearningEntity;
use La\CoreBundle\Entity\Outcome;
use La\CoreBundle\Entity\PersonaMatch;
use La\CoreBundle\Entity\Trace;
use La\CoreBundle\Event\PersonaMatchEvent;
use La\CoreBundle\Events;
use La\CoreBundle\Event\TraceEvent;
use La\CoreBundle\Model\ComparePersona;
use La\CoreBundle\Model\Outcome\ProcessResultVisitor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use La\CoreBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

class TraceController extends Controller
{
    /**
     * @var SecurityContextInterface
     *
     * @DI\Inject("security.context")
     */
    private $securityContext;

    /**
     * @var ProcessResultVisitor $processResultVisitor
     *
     * @DI\Inject("la_learnodex.process_result_visitor")
     */
    private $processResultVisitor;

    /**
     * @var ObjectManager $entityManager
     *
     *  @DI\Inject("doctrine.orm.entity_manager"),
     */
    private $entityManager;

    /**
     * @var ObjectRepository
     *
     * @DI\Inject("la_core.repository.learning_entity")
     */
    private $learningEntityRepository;

    /**
     * @var ObjectRepository
     *
     * @DI\Inject("la_core.repository.outcome")
     */
    private $outcomeRepository;

    /**
     * @var ObjectRepository
     *
     * @DI\Inject("la_core.repository.persona")
     */
    private $personaRepository;

    /**
     * @var ObjectRepository
     *
     * @DI\Inject("la_core.repository.persona_match")
     */
    private $personaMatchRepository;

    /**
     * @var EventDispatcherInterface
     *
     * @DI\Inject("event_dispatcher")
     */
    private $eventDispatcher;

    public function traceAction($outcomeId)
    {
        /** @var $user User */
        $user = $this->securityContext->getToken()->getUser();

        /** @var $outcome Outcome */
        $outcome = $this->outcomeRepository->find($outcomeId);

        if (!$outcome)
        {
            throw $this->createNotFoundException(
                'No outcome found for id ' . $outcomeId
            );
        }
        $trace = new Trace();
        $trace->setUser($user);
        $trace->setOutcome($outcome);
        $trace->setCreatedTime(new \DateTime(date('Y-m-d H:i:s',time())));
        $this->entityManager->persist($trace);
        $this->entityManager->flush();

        $outcome->accept($this->processResultVisitor);

        $this->eventDispatcher->dispatch(Events::TRACE_CREATED, new TraceEvent($trace));

        return $this->redirect($this->generateUrl('card_auto'));
    }

    public function traceButtonAction($id, $caption)
    {
        /** @var $user User */
        $user = $this->securityContext->getToken()->getUser();

        /** @var $learningEntity LearningEntity */
        $learningEntity = $this->learningEntityRepository->find($id);

        /** @var $outcome Outcome */
        foreach ($learningEntity->getOutcomes() as $outcome)
        {
            if (is_a($outcome,'La\CoreBundle\Entity\ButtonOutcome') && $outcome->getCaption() == $caption)
            {
                $trace = new Trace();
                $trace->setUser($user);
                $trace->setOutcome($outcome);
                $trace->setCreatedTime(new \DateTime(date('Y-m-d H:i:s',time())));
                $this->entityManager->persist($trace);
                $this->entityManager->flush();

                $outcome->accept($this->processResultVisitor);
            }
        }

        $this->eventDispatcher->dispatch(Events::TRACE_CREATED, new TraceEvent($trace));

        return $this->redirect($this->generateUrl('card_auto'));
    }

    public function traceUrlAction($id)
    {
        /** @var $user User */
        $user = $this->securityContext->getToken()->getUser();

        /** @var $learningEntity LearningEntity */
        $learningEntity = $this->learningEntityRepository->find($id);

        /** @var $outcome Outcome */
        foreach ($learningEntity->getOutcomes() as $outcome)
        {
            if (is_a($outcome,'La\CoreBundle\Entity\UrlOutcome'))
            {
                $trace = new Trace();
                $trace->setUser($user);
                $trace->setOutcome($outcome);
                $trace->setCreatedTime(new \DateTime(date('Y-m-d H:i:s',time())));
                $this->entityManager->persist($trace);
                $this->entityManager->flush();

                $outcome->accept($this->processResultVisitor);
            }
        }

        $this->eventDispatcher->dispatch(Events::TRACE_CREATED, new TraceEvent($trace));

        return $this->redirect($this->generateUrl('card', array('id'=>$id)));
    }



    public function removeMyTracesAction()
    {
        /** @var $user User */
        $user = $this->securityContext->getToken()->getUser();

        foreach ($user->getPersonas() as $persona) {
            $this->entityManager->remove($persona);
        }
        foreach ($user->getAffinities() as $affinity) {
            $this->entityManager->remove($affinity);
        }
        foreach ($user->getProgress() as $progress) {
            $this->entityManager->remove($progress);
        }
        foreach ($user->getTraces() as $trace) {
            $this->entityManager->remove($trace);
        }
        foreach ($user->getUserProbabilities() as $userProbability) {
            $this->entityManager->remove($userProbability);
        }
        $this->entityManager->flush();

        return $this->redirect($this->generateUrl('homepage'));
    }
}
