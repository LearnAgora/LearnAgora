<?php

namespace La\LearnodexBundle\Controller\Api;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\AgoraBase;
use La\CoreBundle\Entity\LearningEntity;
use La\CoreBundle\Entity\Repository\AgoraRepository;
use La\CoreBundle\Entity\Repository\ProfileRepository;
use La\CoreBundle\Entity\Repository\UserProbabilityRepository;
use La\CoreBundle\Entity\Uplink;
use La\CoreBundle\Entity\User;
use La\CoreBundle\Entity\UserProbability;
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
     * @var ProfileRepository
     */
    private $profileRepository;

    /**
     * @var ObjectRepository
     */
    private $learningEntityRepository;

    /**
     * @var UserProbabilityRepository
     */
    private $userProbabilityRepository;

    /**
     * @var ObjectManager $entityManager
     */
    private $entityManager;


    /**
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext
     * @param AgoraRepository $agoraRepository
     * @param ProfileRepository $profileRepository
     * @param ObjectRepository $learningEntityRepository
     * @param UserProbabilityRepository $userProbabilityRepository
     * @param ObjectManager $entityManager
     *
     * @DI\InjectParams({
     *     "securityContext" = @DI\Inject("security.context"),
     *     "agoraRepository" = @DI\Inject("la_core.repository.agora"),
     *     "profileRepository" = @DI\Inject("la_core.repository.profile"),
     *     "learningEntityRepository" = @DI\Inject("la_core.repository.learning_entity"),
     *     "userProbabilityRepository" = @DI\Inject("la_core.repository.user_probability"),
     *     "entityManager" = @DI\Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(SecurityContextInterface $securityContext, AgoraRepository $agoraRepository, ProfileRepository $profileRepository, ObjectRepository $learningEntityRepository, UserProbabilityRepository $userProbabilityRepository, ObjectManager $entityManager)
    {
        $this->securityContext = $securityContext;
        $this->agoraRepository = $agoraRepository;
        $this->profileRepository = $profileRepository;
        $this->learningEntityRepository = $learningEntityRepository;
        $this->userProbabilityRepository = $userProbabilityRepository;
        $this->entityManager = $entityManager;
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
    public function loadAllAction(Request $request, $id=0)
    {
        /** @var User $user */
        $user = $this->securityContext->getToken()->getUser();
        if ($id) {
            $user = $this->entityManager->getRepository('LaCoreBundle:User')->find($id);
        }

        $data = $this->agoraRepository->findProbabilitiesForUser($user);

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
            $result[] = $entry;
        }

        // sets up the generic pagination
        $pager = new Pagerfanta(new ArrayAdapter($result));

        // this handles the HATEOAS part of same pagination in the next call
        $factory = new PagerfantaFactory();

        return View::create($factory->createRepresentation($pager, new Route($request->get('_route'),array('id'=>$id))), 200);
    }



    public function loadForGoalAction(Request $request, $id)
    {
        /** @var User $user */
        $user = $this->securityContext->getToken()->getUser();

        /* @var LearningEntity $learningEntity */
        $learningEntity = $this->learningEntityRepository->find($id);
        if (null === $learningEntity) {
            throw new NotFoundHttpException(sprintf('LearningEntity resource with id "%d" not found.', $id));
        }

        $result = array();
        /** @var UpLink $downlink */
        foreach ($learningEntity->getDownlinks() as $downlink) {
            $child = $downlink->getChild();

            $userProbabilities = $this->userProbabilityRepository->getUserProbabilities($user, $child);
            if (count($userProbabilities) == 0) {
                $profiles = $this->profileRepository->findAll();
                foreach ($profiles as $profile) {
                    $userProbability = new UserProbability();
                    $userProbability->setUser($user);
                    $userProbability->setProfile($profile);
                    $userProbability->setLearningEntity($child);
                    $userProbability->setProbability(0.2);
                    $userProbabilities[] = $userProbability;
                }
            }
            $result[] = array(
                'learning_entity' => $child,
                'weight' => $downlink->getWeight(),
                'user_probabilities' => $userProbabilities
            );
        }


        // sets up the generic pagination
        $pager = new Pagerfanta(new ArrayAdapter($result));

        // this handles the HATEOAS part of same pagination in the next call
        $factory = new PagerfantaFactory();

        return View::create($factory->createRepresentation($pager, new Route($request->get('_route'), array('id'=>$id))), 200);

    }

}
