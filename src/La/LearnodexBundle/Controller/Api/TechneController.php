<?php

namespace La\LearnodexBundle\Controller\Api;

use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\AgoraBase;
use La\CoreBundle\Entity\Repository\TechneRepository;
use La\CoreBundle\Entity\User;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Hateoas\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class TechneController
{
    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * @var TechneRepository
     */
    private $techneRepository;

    /**
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext
     * @param TechneRepository $techneRepository
     *
     * @DI\InjectParams({
     *     "securityContext" = @DI\Inject("security.context"),
     *     "techneRepository" = @DI\Inject("la_core.repository.techne")
     * })
     */
    public function __construct(SecurityContextInterface $securityContext, TechneRepository $techneRepository)
    {
        $this->securityContext = $securityContext;
        $this->techneRepository = $techneRepository;
    }

    /**
     * @param Request $request
     *
     * @return View
     *
     * @throws NotFoundHttpException if techne agora cannot be found
     *
     * @Doc\ApiDoc(
     *  section="Learnodex",
     *  description="Retrieves the techne agora for the current user",
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when no techne agora is found",
     *  })
     */
    public function loadAllAction(Request $request)
    {
        /** @var User $user */
        $user = $this->securityContext->getToken()->getUser();

        $data = $this->techneRepository->findProbabilitiesForUser($user);

        $result = array();

        foreach ($data as $agora) {
            /* @var AgoraBase $agora */
            $entry['agora'] = $agora;
            $entry['user_probabilities'] = $agora->getUserProbabilities();
            $result[] = $entry;
        }

        // sets up the generic pagination
        $pager = new Pagerfanta(new ArrayAdapter($result));

        // this handles the HATEOAS part of same pagination in the next call
        $factory = new PagerfantaFactory();

        return View::create($factory->createRepresentation($pager, new Route($request->get('_route'))), 200);
    }

}
