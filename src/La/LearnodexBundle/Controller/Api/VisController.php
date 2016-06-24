<?php

namespace La\LearnodexBundle\Controller\Api;

use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\LearningEntity;
use La\CoreBundle\Entity\Repository\AgoraRepository;
use La\CoreBundle\Entity\Repository\LearningEntityRepository;
use La\CoreBundle\Entity\Uplink;
use La\CoreBundle\Model\LearningEntity\GetTypeVisitor;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Hateoas\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class VisController
{
    /**
     * @var AgoraRepository
     */
    private $agoraRepository;

    /**
     * @var LearningEntityRepository
     */
    private $learningEntityRepository;

    /**
     * Constructor.
     *
     * @param LearningEntityRepository $learningEntityRepository
     *
     * @DI\InjectParams({
     *     "learningEntityRepository" = @DI\Inject("la_core.repository.learning_entity")
     * })
     */
    public function __construct(LearningEntityRepository $learningEntityRepository)
    {
        $this->learningEntityRepository = $learningEntityRepository;

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
        $learningEntities = $this->learningEntityRepository->findAll();

        $getTypeVisitor = new GetTypeVisitor();

        $data = array();
        /** @var LearningEntity $learningEntity */
        foreach ($learningEntities as $learningEntity) {
            $downlinks = array();
            /** @var Uplink $downLink */
            foreach ($learningEntity->getDownlinks() as $downLink) {
                $downlinks[] = ['weight'=>$downLink->getWeight(), 'id' =>$downLink->getChild()->getId()];
            }
            $data[] = [
                'id' => $learningEntity->getId(),
                'name' => $learningEntity->getName(),
                'type' => $learningEntity->accept($getTypeVisitor),
                '_embedded' => ['content' => $learningEntity->getContent()],
                'downlinks' => $downlinks
            ];
        }
        $data = ['items'=>$data];
        //$data = [ '_embedded'=>['items'=>$learningEntities] ];
        return View::create($data, 200);
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
