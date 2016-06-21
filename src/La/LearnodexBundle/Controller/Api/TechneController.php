<?php

namespace La\LearnodexBundle\Controller\Api;

use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\AgoraBase;
use La\CoreBundle\Entity\HtmlContent;
use La\CoreBundle\Entity\Repository\ProfileRepository;
use La\CoreBundle\Entity\Repository\TechneRepository;
use La\CoreBundle\Entity\Repository\TraceRepository;
use La\CoreBundle\Entity\Techne;
use La\CoreBundle\Entity\Uplink;
use La\CoreBundle\Entity\User;
use La\CoreBundle\Entity\UserProbability;
use La\LearnodexBundle\Model\Card;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Hateoas\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class TechneController extends Controller
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
     * @var ProfileRepository
     */
    private $profileRepository;

    /**
     * @var ObjectManager $entityManager
     */
    private $entityManager;
    /**
     * @var TraceRepository
     */
    private $traceRepository;

    /**
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext
     * @param TechneRepository $techneRepository
     * @param ProfileRepository $profileRepository
     * @param ObjectManager $entityManager
     * @param TraceRepository $traceRepository
     *
     * @DI\InjectParams({
     *     "securityContext" = @DI\Inject("security.context"),
     *     "techneRepository" = @DI\Inject("la_core.repository.techne"),
     *     "profileRepository" = @DI\Inject("la_core.repository.profile"),
     *     "entityManager" = @DI\Inject("doctrine.orm.entity_manager"),
     *     "traceRepository" = @DI\Inject("la_core.repository.trace")
     * })
     */
    public function __construct(SecurityContextInterface $securityContext, TechneRepository $techneRepository, ProfileRepository $profileRepository, ObjectManager $entityManager, TraceRepository $traceRepository)
    {
        $this->securityContext = $securityContext;
        $this->techneRepository = $techneRepository;
        $this->profileRepository = $profileRepository;
        $this->entityManager = $entityManager;
        $this->traceRepository = $traceRepository;
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
     *  description="Retrieves all techne agora",
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when no techne agora is found",
     *  })
     */
    public function loadAllAction(Request $request)
    {
        $data = $this->techneRepository->findAll();

        // sets up the generic pagination
        $pager = new Pagerfanta(new ArrayAdapter($data));

        // this handles the HATEOAS part of same pagination in the next call
        $factory = new PagerfantaFactory();

        return View::create($factory->createRepresentation($pager, new Route($request->get('_route'))), 200);
    }

    /**
     * @param Request $request
     * @param $id
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
    public function loadAllForUserAction(Request $request, $id=0)
    {
        /** @var User $user */
        $user = $this->securityContext->getToken()->getUser();
        if ($id) {
            $user = $this->entityManager->getRepository('LaCoreBundle:User')->find($id);
        }

        $data = $this->techneRepository->findProbabilitiesForUser($user);

        $result = array();

        foreach ($data as $agora) {
            /* @var AgoraBase $agora */
            $entry['agora'] = $agora;
            $userProbabilities = $agora->getUserProbabilities();
            if (count($userProbabilities) == 0) {
                $profiles = $this->profileRepository->findAll();
                foreach ($profiles as $profile) {
                    $userProbability = new UserProbability();
                    $userProbability->setUser($user);
                    $userProbability->setProfile($profile);
                    $userProbability->setLearningEntity($agora);
                    $userProbability->setProbability(0.2);
                    $userProbabilities[] = $userProbability;
                }
            }
            $entry['user_probabilities'] = $userProbabilities;
            $entry['number_of_traces'] = count($this->traceRepository->findAllForLearningEntity($agora,$user));
            $result[] = $entry;
        }

        usort($result, array($this, "cmp"));

        // sets up the generic pagination
        $pager = new Pagerfanta(new ArrayAdapter($result));
        $pager->setMaxPerPage(20);

        // this handles the HATEOAS part of same pagination in the next call
        $factory = new PagerfantaFactory();

        return View::create($factory->createRepresentation($pager, new Route($request->get('_route'),array('id'=>$id))), 200);
    }

    private function cmp($a, $b)
    {
        /* @var UserProbability $probabilityA */
        $probabilityA = $a['user_probabilities'][0];
        /* @var UserProbability $probabilityB */
        $probabilityB = $b['user_probabilities'][0];
        return $probabilityA->getProbability() < $probabilityB->getProbability();
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
        /** @var Techne $techne */
        if (null === ($techne = $this->techneRepository->find($id))) {
            throw new NotFoundHttpException('Agora could not be found.');
        }

        return View::create($techne, 200);
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
     *      200="Returns the cloned techne agora when successful",
     *      404="Returned when techne agora is not found",
     *  })
     */
    public function cloneAction($id)
    {
        /** @var Techne $techne */
        if (null === ($techne = $this->techneRepository->find($id))) {
            throw new NotFoundHttpException('Agora could not be found.');
        }

        $clone = new Techne();
        $clone->setName("copy of ".$techne->getName());
        $clone->setOwner($this->securityContext->getToken()->getUser());
        $content = new HtmlContent();
        $clone->setContent($content);

        $em = $this->getDoctrine()->getManager();
        $em->persist($clone);
        $em->persist($content);

        $em->flush();

        foreach ($techne->getDownlinks() as $link) {
            /** @var Uplink $link */
            $newLink = new Uplink();
            $newLink->setParent($clone);
            $newLink->setChild($link->getChild());
            $newLink->setWeight($link->getWeight());
            $em->persist($newLink);
        }

        $em->flush();

        $card = new Card($clone);
        return View::create($card, 200);
    }

}
