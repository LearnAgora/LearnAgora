<?php

namespace La\LearnodexBundle\Controller\Api;

use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\AgoraBase;
use La\CoreBundle\Entity\Repository\AgoraRepository;
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
     * @var AgoraRepository
     */
    private $agoraRepository;

    /**
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext
     * @param AgoraRepository $agoraRepository
     *
     * @DI\InjectParams({
     *     "securityContext" = @DI\Inject("security.context"),
     *     "agoraRepository" = @DI\Inject("la_core.repository.agora")
     * })
     */
    public function __construct(SecurityContextInterface $securityContext, AgoraRepository $agoraRepository)
    {
        $this->securityContext = $securityContext;
        $this->agoraRepository = $agoraRepository;
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

        $data = $this->agoraRepository->findProbabilitiesForUser($user);

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
