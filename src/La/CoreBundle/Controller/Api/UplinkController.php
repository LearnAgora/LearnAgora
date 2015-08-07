<?php

namespace La\CoreBundle\Controller\Api;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation as Security;
use La\CoreBundle\Entity\LearningEntity;
use La\CoreBundle\Entity\Uplink;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * @Rest\RouteResource("uplink")
 */
class UplinkController
{
    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * @var ObjectRepository
     */
    private $learningEntityRepository;

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
     * @param SecurityContextInterface $securityContext
     * @param ObjectRepository $learningEntityRepository
     * @param ObjectManager $entityManager
     * @param FormFactoryInterface $formFactory
     * @param RequestStack $requestStack
     *
     *
     * @DI\InjectParams({
     *  "securityContext" = @DI\Inject("security.context"),
     *  "learningEntityRepository" = @DI\Inject("la_core.repository.learning_entity"),
     *  "entityManager" = @DI\Inject("doctrine.orm.entity_manager"),
     *  "formFactory" = @DI\Inject("form.factory"),
     *  "requestStack" = @DI\Inject("request_stack")
     * })
     */
    public function __construct(SecurityContextInterface $securityContext, ObjectRepository $learningEntityRepository, ObjectManager $entityManager, FormFactoryInterface $formFactory, RequestStack $requestStack)
    {
        $this->securityContext = $securityContext;
        $this->learningEntityRepository = $learningEntityRepository;
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->requestStack = $requestStack;
    }

    /**
     * Retrieve a all Uplink resources for a given Learning Entity.
     *
     * @param int $id
     *
     * @return Uplink[]
     *
     * @Security\Secure(roles="ROLE_API")
     *
     * @Doc\ApiDoc(
     *  section="Core",
     *  resource=true,
     *  description="Retrieve a all Uplink resources for a given Learning Entity",
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when the Learning Entity resource is not found",
     *  })
     * )
     */
    public function getAction($id)
    {
        return $this->getContentOr404($id);
    }

    /**
     * Not Implemented.
     *
     * @return null
     *
     * @Security\Secure(roles="ROLE_API")
     *
     * @Doc\ApiDoc(
     *  section="Core",
     *  description="Not Implemented",
     *  statusCodes={
     *      410="Gone"
     *  })
     * )
     */
    public function cgetAction()
    {
        return View::create(null, 410);
    }

    /**
     * Not Implemented.
     *
     * @return null
     *
     * @Security\Secure(roles="ROLE_API")
     *
     * @Doc\ApiDoc(
     *  section="Core",
     *  description="Not Implemented",
     *  statusCodes={
     *      410="Gone"
     *  })
     * )
     */
    public function postAction()
    {
        return View::create(null, 410);
    }

    /**
     * Not Implemented.
     *
     * @return null
     *
     * @Security\Secure(roles="ROLE_API")
     *
     * @Doc\ApiDoc(
     *  section="Core",
     *  description="Not Implemented",
     *  statusCodes={
     *      410="Gone"
     *  })
     * )
     */
    public function putAction()
    {
        return View::create(null, 410);
    }

    /**
     * Not Implemented.
     *
     * @return null
     *
     * @Security\Secure(roles="ROLE_API")
     *
     * @Doc\ApiDoc(
     *  section="Core",
     *  description="Not Implemented",
     *  statusCodes={
     *      410="Gone"
     *  })
     * )
     */
    public function patchAction()
    {
        return View::create(null, 410);
    }

    /**
     * Not Implemented.
     *
     * @return null
     *
     * @Security\Secure(roles="ROLE_API")
     *
     * @Doc\ApiDoc(
     *  section="Core",
     *  description="Not Implemented",
     *  statusCodes={
     *      410="Gone"
     *  })
     * )
     */
    public function deleteAction()
    {
        return View::create(null, 410);
    }

    /**
     * @param int $id
     *
     * @return Uplink[]
     *
     * @throws NotFoundHttpException if the requested resource cannot be found
     */
    private function getContentOr404($id)
    {
        /* @var LearningEntity $learningEntity */
        $learningEntity = $this->learningEntityRepository->find($id);
        if (null === $learningEntity) {
            throw new NotFoundHttpException(sprintf('LearningEntity resource with id "%d" not found.', $id));
        }
        $children = $learningEntity->getDownlinks();
        $parents = $learningEntity->getUplinks();

        return array(
            'children' => $children,
            'parents' => $parents
        );
    }

}
