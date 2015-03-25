<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\CoreBundle\Model\Probability;


use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\Common\Persistence\ObjectManager;
use La\CoreBundle\Entity\Affinity;
use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\Outcome;
use La\CoreBundle\Entity\Repository\UserProbabilityRepository;
use La\CoreBundle\Entity\User;
use La\CoreBundle\Event\MissingUserProbabilityEvent;
use La\CoreBundle\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


/**
 * @DI\Service
 */
class UserProbabilities
{
    /**
     * @var User
     */
    private $user;
    /**
     * @var Agora
     */
    private $agora;
    /**
     * @var Bayes
     */
    private $bayes;

    /**
     * @var UserProbabilityRepository
     */
    private $userProbabilityRepository;

    /**
     * @var ObjectManager
     */
    private $entityManager;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * Constructor.
     *
     * @param UserProbabilityRepository $userProbabilityRepository
     * @param ObjectManager $entityManager
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @DI\InjectParams({
     *  "userProbabilityRepository" = @DI\Inject("la_core.repository.userProbability"),
     *  "entityManager" = @DI\Inject("doctrine.orm.entity_manager"),
     *  "eventDispatcher" = @DI\Inject("event_dispatcher")
     * })
     */
    public function __construct(UserProbabilityRepository $userProbabilityRepository, ObjectManager $entityManager, EventDispatcherInterface $eventDispatcher) {
        $this->userProbabilityRepository = $userProbabilityRepository;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->bayes = new Bayes();
    }

    public function setUser(User $user) {
        $this->user = $user;
    }
    public function setLearningEntity(Agora $agora) {
        $this->agora = $agora;
    }
    public function processOutcome(Outcome $outcome) {
        if (is_null($this->user) || is_null($this->agora)) {
            // @TODO raise exception
        }

        $bayesData = $this->userProbabilityRepository->loadProbabilitiesFor($this->user, $this->agora, $outcome);
        if ($bayesData->hasNullUserProbability()) {
            $this->eventDispatcher->dispatch(Events::MISSING_USER_PROBABILITY, new MissingUserProbabilityEvent($this->user, $this->agora, $bayesData));
        }

        if ($bayesData->hasNullOutcomeProbability()) {
            $bayesData->updateOutcomeProbabilities();
        }

        $bayesData->process();

        foreach ($bayesData->getUserProbabilities() as $userProbability) {
            $this->entityManager->persist($userProbability);
        }

        $topUserProbability = $bayesData->getTopUserProbability();
        /* @var Affinity $affinity */
        $affinity = $this->entityManager->getRepository('LaCoreBundle:Affinity')->findOneBy(array('user'=>$this->user,'agora'=>$this->agora));
        $affinity->setValue(100*$topUserProbability->getProbability());
        $affinity->setProfile($topUserProbability->getProfile());
        $this->entityManager->persist($affinity);

        $this->entityManager->flush();
    }



}
