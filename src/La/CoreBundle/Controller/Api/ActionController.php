<?php

namespace La\CoreBundle\Controller\Api;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation as Security;
use La\CoreBundle\Entity\Action;
use La\LearnodexBundle\Model\Visitor\InitialiseLearningEntityNoPersistVisitor;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * @Rest\RouteResource("action")
 */
class ActionController
{
    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * @var ObjectRepository
     */
    private $actionRepository;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * @var InitialiseLearningEntityNoPersistVisitor
     */
    private $initialiseLearningEntityVisitor;

    /**
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext
     * @param ObjectRepository $actionRepository
     * @param ObjectManager $entityManager
     * @param FormFactoryInterface $formFactory
     * @param RequestStack $requestStack
     * @param InitialiseLearningEntityNoPersistVisitor $initialiseLearningEntityVisitor
     *
     * @DI\InjectParams({
     *  "securityContext" = @DI\Inject("security.context"),
     *  "actionRepository" = @DI\Inject("la_core.repository.action"),
     *  "entityManager" = @DI\Inject("doctrine.orm.entity_manager"),
     *  "formFactory" = @DI\Inject("form.factory"),
     *  "requestStack" = @DI\Inject("request_stack"),
     *  "initialiseLearningEntityVisitor" = @DI\Inject("la_learnodex.initialise_learning_entity_visitor_no_persist")
     * })
     */
    public function __construct(SecurityContextInterface $securityContext, ObjectRepository $actionRepository, ObjectManager $entityManager, FormFactoryInterface $formFactory, RequestStack $requestStack, InitialiseLearningEntityNoPersistVisitor $initialiseLearningEntityVisitor)
    {
        $this->securityContext = $securityContext;
        $this->actionRepository = $actionRepository;
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->requestStack = $requestStack;
        $this->initialiseLearningEntityVisitor = $initialiseLearningEntityVisitor;
    }

    /**
     * Retrieve a single Action resource.
     *
     * @param int $id
     *
     * @return Action
     *
     * @Security\Secure(roles="ROLE_API")
     *
     * @Doc\ApiDoc(
     *  section="Core",
     *  resource=true,
     *  description="Retrieve a single Action resource",
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when the Action resource is not found",
     *  })
     * )
     */
    public function getAction($id)
    {
        return $this->getContentOr404($id);
    }

    /**
     * Retrieve all answer resources.
     *
     * @return Action[]
     *
     * @Security\Secure(roles="ROLE_API")
     *
     * @Doc\ApiDoc(
     *  section="Core",
     *  description="Retrieve all Action resources",
     *  statusCodes={
     *      200="Returned when successful"
     *  })
     * )
     */
    public function cgetAction()
    {
        $actions = $this->actionRepository->findAll();
        $data = [ '_embedded'=>['items'=>$actions] ];
        return View::create($data, 200);
    }

    /**
     * Create a new Action resource.
     *
     * @return View
     *
     * @Security\Secure(roles="ROLE_API")
     *
     * @Doc\ApiDoc(
     *  section="Core",
     *  description="Create a new Action resource",
     *  input="La\CoreBundle\Forms\ActionType",
     *  output="La\CoreBundle\Entity\Action",
     *  statusCodes={
     *      201="Returned when successful",
     *      400="Returned when the request cannot be processed",
     *      422="Returned when validation fails",
     *  })
     * )
     */
    public function postAction()
    {
        $action = new Action();
        $action->accept($this->initialiseLearningEntityVisitor);
        return $this->processForm($action, 201);
    }

    /**
     * @param int $id
     *
     * @return View
     *
     * @Security\Secure(roles="ROLE_API")
     *
     * @Doc\ApiDoc(
     *  section="Core",
     *  description="Update a Action resource",
     *  input="La\CoreBundle\Forms\ActionType",
     *  output="La\CoreBundle\Entity\Action",
     *  statusCodes={
     *      200="Returned when successful",
     *      400="Returned when the request cannot be processed",
     *      422="Returned when validation fails",
     *  })
     * )
     */
    public function putAction($id)
    {
        return $this->processForm($this->getContentOr404($id), 200);
    }

    /**
     * @param int $id
     *
     * @return View
     *
     * @Security\Secure(roles="ROLE_API")
     *
     * @Doc\ApiDoc(
     *  section="Core",
     *  description="Partially update a Action resource",
     *  input="La\CoreBundle\Forms\ActionType",
     *  output="La\CoreBundle\Entity\Action",
     *  statusCodes={
     *      200="Returned when successful",
     *      400="Returned when the request cannot be processed",
     *      422="Returned when validation fails",
     *  })
     * )
     */
    public function patchAction($id)
    {
        return $this->processForm($this->getContentOr404($id), 200, false);
    }

    /**
     * @param int $id
     *
     * @return View
     *
     * @Security\Secure(roles="ROLE_API")
     *
     * @Doc\ApiDoc(
     *  section="Core",
     *  description="Delete a Action resource",
     *  statusCodes={
     *      204="Returned when successful",
     *      404="Returned when the Answer resource is not found",
     *  })
     * )
     */
    public function deleteAction($id)
    {
        $this->entityManager->remove($this->getContentOr404($id));
        $this->entityManager->flush();

        return View::create(null, 204);
    }

    /**
     * @param int $id
     *
     * @return Action
     *
     * @throws NotFoundHttpException if the requested resource cannot be found
     */
    private function getContentOr404($id)
    {
        $content = $this->actionRepository->find($id);

        if (null === $content) {
            throw new NotFoundHttpException(sprintf('Action resource with id "%d" not found.', $id));
        }

        return $content;
    }

    /**
     * @param Action $action
     * @param int $statusCode
     * @param bool $clearMissing
     *
     * @return View
     */
    private function processForm(Action $action, $statusCode, $clearMissing = true)
    {
        $request = $this->requestStack->getCurrentRequest();

        if (null === $request) {
            return View::create(null, 400);
        }

        $action->setName("I should see this in postmanny");
        $form = $this->formFactory->create('form_action', $action);

        $form->submit($request->get('form_action'), $clearMissing);

        if ($form->isValid()) {
            if ($action->getId() == null) {
                $user = $this->securityContext->getToken()->getUser();
                $action->setOwner($user);
            }
            $this->entityManager->persist($action);
            $this->entityManager->flush();

            return View::create($action, $statusCode);
        }

        return View::create($form, 422);
    }
}
