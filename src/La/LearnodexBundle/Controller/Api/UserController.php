<?php

namespace La\LearnodexBundle\Controller\Api;

use Doctrine\Common\Persistence\ObjectManager;
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
     * @var ObjectManager $entityManager
     */
    private $entityManager;

    /**
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext
     * @param ObjectManager $entityManager
     *
     * @DI\InjectParams({
     *     "securityContext" = @DI\Inject("security.context"),
     *     "entityManager" = @DI\Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(SecurityContextInterface $securityContext, ObjectManager $entityManager)
    {
        $this->securityContext = $securityContext;
        $this->entityManager = $entityManager;
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
        if (null === $user) {
            throw new NotFoundHttpException('User could not be found.');
        }

        return View::create(array('user'=>$user), 200);
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
    public function loadAllAction()
    {
        return $this->returnAllUsers();
    }

    public function giveRoleAction($id, $role)
    {
        $user = $this->entityManager->getRepository('LaCoreBundle:User')->find($id);
        $user->addRole($role);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $this->returnAllUsers();
    }

    public function takeRoleAction($id, $role)
    {
        $user = $this->entityManager->getRepository('LaCoreBundle:User')->find($id);
        $user->removeRole($role);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $this->returnAllUsers();
    }

    private function returnAllUsers() {
        $users = $this->entityManager->getRepository('LaCoreBundle:User')->findBy(array('enabled'=>'1'));
        $data = [ '_embedded'=>['users'=>$users] ];
        return View::create($data, 200);
    }


}
