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
 * @Rest\RouteResource("downlink")
 */
class DownlinkController
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
     * Retrieve a all Downlink resources for a given Learning Entity.
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
     *  description="Retrieve a all Downlink resources for a given Learning Entity",
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
     * create a new downlink.
     *
     * @param int $id
     *
     * @return Uplink[]
     *
     * @Security\Secure(roles="ROLE_API")
     *
     * @Doc\ApiDoc(
     *  section="Core",
     *  description="create a new downlink",
     *  statusCodes={
     *      201="Returned when successful",
     *      400="Returned when the request cannot be processed",
     *  })
     * )
     */
    public function postAction()
    {
        $request = $this->requestStack->getCurrentRequest();

        if (null === $request) {
            return View::create(null, 400);
        }

        $data = $request->get('form_uplink');
        $childId = $data['child'];
        $parentId = $data['parent'];
        $weight = $data['weight'];
        $uplink = new Uplink();
        /** @var LearningEntity $parentEntity */
        $parentEntity = $this->learningEntityRepository->find($parentId);
        /** @var LearningEntity $childEntity */
        $childEntity = $this->learningEntityRepository->find($childId);
        $uplink->setParent($parentEntity);
        $uplink->setChild($childEntity);
        $uplink->setWeight($weight);
        $this->entityManager->persist($uplink);
        $this->entityManager->flush();

        return $this->getContentOr404($parentId);
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
     * Deletes a downlink for a learningEntity.
     *
     * @param int $id
     * @param int $linkId
     * @return Uplink[]
     *
     * @Security\Secure(roles="ROLE_API")
     *
     * @Doc\ApiDoc(
     *  section="Core",
     *  description="Deletes a downlink for a learningEntity",
     *  statusCodes={
     *      201="Returned when successful",
     *      404="LearningEntity not found"
     *  }
     * )
     */
    public function deleteAction($id,$linkId)
    {
        /** @var Uplink $link */
        $link = $this->entityManager->getRepository('LaCoreBundle:Uplink')->find($linkId);
        /* @var LearningEntity $learningEntity */
        $learningEntity = $this->learningEntityRepository->find($id);
        $learningEntity->removeDownlink($link);
        $this->entityManager->remove($link);
        $this->entityManager->flush();
        return $this->getContentOr404($id);
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
