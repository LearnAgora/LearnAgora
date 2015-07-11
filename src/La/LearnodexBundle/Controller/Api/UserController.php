<?php

namespace La\LearnodexBundle\Controller\Api;

use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\User;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\SecurityContextInterface;

class UserController
{
    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext
     *
     * @DI\InjectParams({
     *     "securityContext" = @DI\Inject("security.context")
     * })
     */
    public function __construct(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    /**
     * @return View
     *
     * @throws NotFoundHttpException if no user is logged in
     *
     * @Doc\ApiDoc(
     *  section="Learnodex",
     *  description="Retrieves the profile for the current user",
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when no user is found",
     *  })
     */
    public function loadProfileAction()
    {
        /** @var User $user */
        $user = $this->securityContext->getToken()->getUser();
        $roles = $this->securityContext->getToken()->getRoles();
        if (null === $user) {
            throw new NotFoundHttpException('User could not be found.');
        }

        return View::create(array('user'=>$user, 'roles'=>$roles), 200);
    }



}
