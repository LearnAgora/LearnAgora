<?php

namespace La\CoreBundle\Controller\Api;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\UrlContent;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Rest\RouteResource("url-content")
 */
class UrlContentController
{
    /**
     * @var ObjectRepository
     */
    private $urlContentRepository;

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
     * Constructor.
     *
     * @param ObjectRepository $urlContentRepository
     * @param ObjectManager $entityManager
     * @param FormFactoryInterface $formFactory
     * @param RequestStack $requestStack
     *
     * @DI\InjectParams({
     *  "urlContentRepository" = @DI\Inject("la_core.repository.url_content"),
     *  "entityManager" = @DI\Inject("doctrine.orm.entity_manager"),
     *  "formFactory" = @DI\Inject("form.factory"),
     *  "requestStack" = @DI\Inject("request_stack")
     * })
     */
    public function __construct(ObjectRepository $urlContentRepository, ObjectManager $entityManager, FormFactoryInterface $formFactory, RequestStack $requestStack)
    {
        $this->urlContentRepository = $urlContentRepository;
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->requestStack = $requestStack;
    }

    /**
     * Retrieve a single url-content resource.
     *
     * @param int $id
     *
     * @return UrlContent
     *
     * @throws NotFoundHttpException if the requested resource cannot be found
     *
     * @Doc\ApiDoc(
     *  section="Core",
     *  resource=true,
     *  description="Retrieve a single UrlContent resource"
     * )
     */
    public function getAction($id)
    {
        $content = $this->urlContentRepository->find($id);

        if (null === $content) {
            throw new NotFoundHttpException(sprintf('UrlContent resource with id "%d" not found.', $id));
        }

        return $content;
    }

    /**
     * Retrieve all url-content resources.
     *
     * @return UrlContent[]
     *
     * @Doc\ApiDoc(
     *  section="Core",
     *  description="Retrieve all UrlContent resources"
     * )
     */
    public function cgetAction()
    {
        return $this->urlContentRepository->findAll();
    }

    /**
     * Create a new url-content resource.
     *
     * @return UrlContent
     *
     * @Doc\ApiDoc(
     *  section="Core",
     *  description="Create a new UrlContent resource",
     *  input="La\CoreBundle\Forms\UrlContentType",
     *  output="La\CoreBundle\Entity\UrlContent"
     * )
     */
    public function postAction()
    {
        return $this->processForm(new UrlContent());
    }

    private function processForm(UrlContent $content)
    {
        $form = $this->formFactory->create('form_url_content', $content);
        $form->handleRequest($this->requestStack->getCurrentRequest());

        if ($form->isValid()) {
            $this->entityManager->persist($content);
            $this->entityManager->flush();

            return $content;
        }
    }
}
