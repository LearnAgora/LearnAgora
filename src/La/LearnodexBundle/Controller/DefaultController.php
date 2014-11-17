<?php

namespace La\LearnodexBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation as Security;
use La\CoreBundle\Entity\Answer;
use La\CoreBundle\Entity\LearningEntity;
use La\CoreBundle\Entity\Outcome;
use La\CoreBundle\Entity\Trace;
use La\CoreBundle\Entity\User;
use La\CoreBundle\Model\Outcome\ProcessResultVisitor;
use La\LearnodexBundle\Model\Card;
use La\LearnodexBundle\Model\Exception\CardNotFoundException;
use La\LearnodexBundle\Model\RandomCardProviderInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

class DefaultController
{
    /**
     * @var SecurityContextInterface
     *
     * @DI\Inject("security.context")
     */
    private $securityContext;

    /**
     * @var RequestStack
     *
     * @DI\Inject("request_stack")
     */
    private $requestStack;

    /**
     * @var RouterInterface
     *
     * @DI\Inject("router")
     */
    private $router;

    /**
     * @var EngineInterface
     *
     * @DI\Inject("templating")
     */
    private $templating;

    /**
     * @var ObjectManager
     *
     * @DI\Inject("doctrine.orm.entity_manager")
     */
    private $entityManager;

    /**
     * @var ObjectRepository
     *
     * @DI\Inject("la_core.repository.action")
     */
    private $actionRepository;

    /**
     * @var ObjectRepository
     *
     * @DI\Inject("la_core.repository.learning_entity")
     */
    private $learningEntityRepository;

    /**
     * @var ObjectRepository
     *
     * @DI\Inject("la_core.repository.answer")
     */
    private $answerRepository;

    /**
     * @var RandomCardProviderInterface
     *
     * @DI\Inject("random_card_provider")
     */
    private $cardProvider;

    /**
     * @var ProcessResultVisitor
     *
     * @DI\Inject("la_learnodex.process_result_visitor")
     */
    private $processResultVisitor;

    /**
     * @Security\Secure(roles="ROLE_USER")
     */
    public function indexAction()
    {
        return $this->templating->renderResponse('LaLearnodexBundle:Default:index.html.twig');
    }

    /**
     * @param int $id
     *
     * @return Response
     *
     * @Security\Secure(roles="ROLE_USER")
     */
    public function cardAction($id = 0)
    {
        /** @var $learningEntity LearningEntity */
        if ($id) {
            $learningEntity = $this->learningEntityRepository->find($id);
            $card = new Card($learningEntity);
        } else {
            try {
                $card = $this->cardProvider->getCard();
            } catch (CardNotFoundException $e) {
                return $this->templating->renderResponse('LaLearnodexBundle:Card:NoCardsLeft.html.twig');
            }
        }

        return $this->templating->renderResponse('LaLearnodexBundle:Card:Card.html.twig', array(
            'card' => $card,
        ));
    }

    /**
     * @Security\Secure(roles="ROLE_USER")
     */
    public function traceAction()
    {
        /** @var $user User */
        $user = $this->securityContext->getToken()->getUser();
        $request = $this->requestStack->getCurrentRequest();
        $answerId = $request->request->get('answer');

        /** @var $answer Answer */
        $answer = $this->answerRepository->find($answerId);
        /** @var $outcome Outcome */
        foreach ($answer->getOutcomes() as $outcome) {
            $trace = new Trace();
            $trace->setUser($user);
            $trace->setOutcome($outcome);
            $this->entityManager->persist($trace);
            $this->entityManager->flush();
            foreach ($outcome->getResults() as $result) {
                $result->accept($this->processResultVisitor);
            }
        }

        return new RedirectResponse($this->router->generate('card_auto'));
    }

    public function dnaAction() {
        return $this->templating->renderResponse('LaLearnodexBundle:Default:dna.html.twig');
    }
}
