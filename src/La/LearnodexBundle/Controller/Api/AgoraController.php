<?php

namespace La\LearnodexBundle\Controller\Api;

use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\Repository\AgoraRepository;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Hateoas\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class AgoraController
{
    /**
     * @var AgoraRepository
     */
    private $agoraRepository;

    /**
     * Constructor.
     *
     * @param AgoraRepository $agoraRepository
     *
     * @DI\InjectParams({
     *     "agoraRepository" = @DI\Inject("la_core.repository.agora")
     * })
     */
    public function __construct(AgoraRepository $agoraRepository)
    {
        $this->agoraRepository = $agoraRepository;

    }

    /**
     *
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
        $data = $this->agoraRepository->findAll();

        // sets up the generic pagination
        $pager = new Pagerfanta(new ArrayAdapter($data));

        // this handles the HATEOAS part of same pagination in the next call
        $factory = new PagerfantaFactory();

        return View::create($factory->createRepresentation($pager, new Route($request->get('_route'))), 200);
    }

    /**
     * @param int $id
     *
     * @return View
     *
     * @throws NotFoundHttpException if techne agora cannot be found
     *
     * @Doc\ApiDoc(
     *  section="Learnodex",
     *  description="Retrieves the techne for the given id",
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when no techne agora is found",
     *  })
     */
    public function loadAction($id)
    {
        /** @var Agora $agora */
        if (null === ($agora = $this->agoraRepository->find($id))) {
            throw new NotFoundHttpException('Agora could not be found.');
        }

        return View::create($agora, 200);
    }

}
