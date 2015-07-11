<?php

namespace La\LearnodexBundle\Controller\Api;

use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\Repository\ActionRepository;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Hateoas\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class ActionController
{
    /**
     * @var ActionRepository
     */
    private $actionRepository;

    /**
     * Constructor.
     *
     * @param ActionRepository $actionRepository
     *
     * @DI\InjectParams({
     *     "actionRepository" = @DI\Inject("la_core.repository.action")
     * })
     */
    public function __construct(ActionRepository $actionRepository)
    {
        $this->actionRepository = $actionRepository;

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
        $data = $this->actionRepository->findAll();

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
        if (null === ($agora = $this->actionRepository->find($id))) {
            throw new NotFoundHttpException('ACtion could not be found.');
        }

        return View::create($agora, 200);
    }

}
