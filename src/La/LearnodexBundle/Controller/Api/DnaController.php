<?php

namespace La\LearnodexBundle\Controller\Api;

use Doctrine\Common\Persistence\ObjectRepository;
use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\User;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Hateoas\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DnaController
{
    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * @var ObjectRepository
     */
    private $affinityRepository;

    /**
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext
     * @param ObjectRepository $affinityRepository
     *
     * @DI\InjectParams({
     *     "securityContext" = @DI\Inject("security.context"),
     *     "affinityRepository" = @DI\Inject("la_core.repository.affinity")
     * })
     */
    public function __construct(SecurityContextInterface $securityContext, ObjectRepository $affinityRepository)
    {
        $this->securityContext = $securityContext;
        $this->affinityRepository = $affinityRepository;
    }

    /**
     * @param Request $request
     *
     * @return View
     *
     * @throws NotFoundHttpException if dna cannot be found
     *
     * @Doc\ApiDoc(
     *  section="Learnodex",
     *  description="Retrieves the dna for the current user",
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when no dna is found",
     *  })
     */
    public function loadAllAction(Request $request)
    {
        /** @var User $user */
        $user = $this->securityContext->getToken()->getUser();

        // sets up the generic pagination
        $pager = new Pagerfanta(new ArrayAdapter($this->affinityRepository->findBy(array("user"=>$user))));

        // this handles the HATEOAS part of same pagination in the next call
        $factory = new PagerfantaFactory();

        return View::create($factory->createRepresentation($pager, new Route($request->get('_route'))), 200);

        //die($user->getId());
        //return View::create($card, 200);
    }
}
