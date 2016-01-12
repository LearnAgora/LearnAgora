<?php

namespace La\LearnodexBundle\Controller\Api;

use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Repository\TechneRepository;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Hateoas\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class SearchController
{
    /**
     * @var TechneRepository
     */
    private $techneRepository;

    /**
     * Constructor.
     *
     * @param TechneRepository $techneRepository
     *
     * @DI\InjectParams({
     *     "techneRepository" = @DI\Inject("la_core.repository.techne")
     * })
     */
    public function __construct(TechneRepository $techneRepository)
    {
        $this->techneRepository = $techneRepository;

    }

    /**
     *
     * @param Request $request
     * @param $query
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
    public function TechneAction($query, Request $request)
    {
        $data = $this->techneRepository->search($query);

        // sets up the generic pagination
        $pager = new Pagerfanta(new ArrayAdapter($data));

        // this handles the HATEOAS part of same pagination in the next call
        $factory = new PagerfantaFactory();

        return View::create($factory->createRepresentation($pager, new Route($request->get('_route'),array('query'=>$query))), 200);
    }


}
