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
use La\CoreBundle\Model\ComparePersona;
use La\CoreBundle\Model\Outcome\ProcessResultVisitor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use La\CoreBundle\Entity\User;
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
     * @DI\Inject("la_core.repository.answer")
     */
    private $answerRepository;

    /**
     * @var ObjectRepository
     *
     * @DI\Inject("la_core.repository.learning_entity")
     */
    private $learningEntityRepository;

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

    public function traceAction($answerId)
    {
        /** @var $user User */
        $user = $this->securityContext->getToken()->getUser();

        $answer = $this->answerRepository->find($answerId);
        /** @var $outcome Outcome */
        foreach ($answer->getOutcomes() as $outcome) {
            $trace = new Trace();
            $trace->setUser($user);
            $trace->setOutcome($outcome);
            $trace->setCreatedTime(new \DateTime(date('Y-m-d H:i:s',time())));
            $this->entityManager->persist($trace);
            $this->entityManager->flush();
            foreach ($outcome->getResults() as $result) {
                $result->accept($this->processResultVisitor);
            }
        }

        $this->compareWithPersona($user);

        return $this->redirect($this->generateUrl('card_auto'));
    }

    public function traceButtonAction($id, $caption)
    {
        /** @var $user User */
        $user = $this->securityContext->getToken()->getUser();

        /** @var $learningEntity LearningEntity */
        $learningEntity = $this->learningEntityRepository->find($id);

        /** @var $outcome Outcome */
        foreach ($learningEntity->getOutcomes() as $outcome) {
            if (is_a($outcome,'La\CoreBundle\Entity\ButtonOutcome') && $outcome->getCaption() == $caption) {
                $trace = new Trace();
                $trace->setUser($user);
                $trace->setOutcome($outcome);
                $trace->setCreatedTime(new \DateTime(date('Y-m-d H:i:s',time())));
                $this->entityManager->persist($trace);
                $this->entityManager->flush();
                foreach ($outcome->getResults() as $result) {
                    $result->accept($this->processResultVisitor);
                }
            }
        }

        $this->compareWithPersona($user);

        return $this->redirect($this->generateUrl('card_auto'));
    }

    public function traceUrlAction($id)
    {
        /** @var $user User */
        $user = $this->securityContext->getToken()->getUser();

        /** @var $learningEntity LearningEntity */
        $learningEntity = $this->learningEntityRepository->find($id);

        /** @var $outcome Outcome */
        foreach ($learningEntity->getOutcomes() as $outcome) {
            if (is_a($outcome,'La\CoreBundle\Entity\UrlOutcome')) {
                $trace = new Trace();
                $trace->setUser($user);
                $trace->setOutcome($outcome);
                $trace->setCreatedTime(new \DateTime(date('Y-m-d H:i:s',time())));
                $this->entityManager->persist($trace);
                $this->entityManager->flush();
                foreach ($outcome->getResults() as $result) {
                    $result->accept($this->processResultVisitor);
                }
            }
        }

        $this->compareWithPersona($user);

        return $this->redirect($this->generateUrl('card', array('id'=>$id)));
    }

    private function compareWithPersona($user)
    {
        $personalities = $this->personaRepository->findAll();

        $comparePersona = new ComparePersona();

        foreach ($personalities as $personality) {
            $difference = $comparePersona->compare($user,$personality->getUser());
            $personaMatch = $this->personaMatchRepository->findOneBy(
                array(
                    'user' => $user,
                    'persona' => $personality
                )
            );
            if (!$personaMatch) {
                $personaMatch = new PersonaMatch();
                $personaMatch->setUser($user);
                $personaMatch->setPersona($personality);
            }
            $personaMatch->setDifference($difference);
            $this->entityManager->persist($personaMatch);
        }
        $this->entityManager->flush();
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
        $this->entityManager->flush();

        return $this->redirect($this->generateUrl('homepage'));
    }
}
