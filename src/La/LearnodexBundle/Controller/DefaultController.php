<?php

namespace La\LearnodexBundle\Controller;

use Doctrine\Common\Persistence\ObjectRepository;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation as Security;
use La\CoreBundle\Entity\LearningEntity;
use La\LearnodexBundle\Model\Card;
use La\LearnodexBundle\Model\Exception\CardNotFoundException;
use La\LearnodexBundle\Model\RandomCardProviderInterface;
use La\LearnodexBundle\Model\Visitor\UpLinkManagerVisitor;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
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
     * @var EngineInterface
     *
     * @DI\Inject("templating")
     */
    private $templating;

    /**
     * @var ObjectRepository
     *
     * @DI\Inject("la_core.repository.learning_entity")
     */
    private $learningEntityRepository;

    /**
     * @var ObjectRepository
     *
     * @DI\Inject("la_core.repository.progress")
     */
    private $progressRepository;

    /**
     * @var UpLinkManagerVisitor
     *
     * @DI\Inject("la_core.uplink_manager_visitor")
     */
    private $upLinkManager;

    /**
     * @var RandomCardProviderInterface
     *
     * @DI\Inject("random_card_provider")
     */
    private $cardProvider;

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

        $learningEntity = $card->getLearningEntity();

        $user = $this->securityContext->getToken()->getUser();
        $card->setProgress($this->progressRepository->findOneBy(
            array(
                'user' => $user,
                'learningEntity' => $learningEntity,
            )
        ));

        $learningEntity->accept($this->upLinkManager);


        return $this->templating->renderResponse('LaLearnodexBundle:Card:Card.html.twig', array(
            'card'          => $card,
            'upLinkManager' => $this->upLinkManager,
        ));
    }

    public function dnaAction() {
        return $this->templating->renderResponse('LaLearnodexBundle:Default:dna.html.twig');
    }
}
