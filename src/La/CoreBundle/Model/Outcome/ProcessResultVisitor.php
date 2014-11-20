<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\CoreBundle\Model\Outcome;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Action;
use La\CoreBundle\Entity\Affinity;
use La\CoreBundle\Entity\AffinityResult;
use La\CoreBundle\Entity\NextEntityResult;
use La\CoreBundle\Entity\Objective;
use La\CoreBundle\Entity\Progress;
use La\CoreBundle\Entity\ProgressResult;
use La\CoreBundle\Entity\Trace;
use La\CoreBundle\Entity\Uplink;
use La\CoreBundle\Entity\LearningEntity;
use La\CoreBundle\Entity\User;
use La\CoreBundle\Model\LearningEntity\GetTypeVisitor;
use La\CoreBundle\Model\LearningEntity\ProgressManager;
use La\CoreBundle\Visitor\ActionVisitorInterface;
use La\CoreBundle\Visitor\AffinityResultVisitorInterface;
use La\CoreBundle\Visitor\NextEntityResultVisitorInterface;
use La\CoreBundle\Visitor\ObjectiveVisitorInterface;
use La\CoreBundle\Visitor\ProgressResultVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * @DI\Service("la_learnodex.process_result_visitor")
 */
class ProcessResultVisitor implements
    VisitorInterface,
    AffinityResultVisitorInterface,
    ProgressResultVisitorInterface,
    NextEntityResultVisitorInterface,
    ActionVisitorInterface,
    ObjectiveVisitorInterface
{
    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * @var ObjectRepository
     */
    private $affinityRepository;

    /**
     * @var ObjectRepository
     */
    private $progressRepository;

    /**
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext
     * @param ObjectManager $entityManager
     * @param ObjectRepository $affinityRepository
     * @param ObjectRepository $progressRepository
     *
     * @DI\InjectParams({
     *  "securityContext" = @DI\Inject("security.context"),
     *  "entityManager" = @DI\Inject("doctrine.orm.entity_manager"),
     *  "affinityRepository" = @DI\Inject("la_core.repository.affinity"),
     *  "progressRepository" = @DI\Inject("la_core.repository.progress")
     * })
     */
    public function __construct(SecurityContextInterface $securityContext, ObjectManager $entityManager, ObjectRepository $affinityRepository, ObjectRepository $progressRepository)
    {
        $this->securityContext = $securityContext;
        $this->entityManager = $entityManager;
        $this->affinityRepository = $affinityRepository;
        $this->progressRepository = $progressRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function visitAffinityResult(AffinityResult $result)
    {
        $userId = $this->securityContext->getToken()->getUser()->getId();
        $uplinks = $result->getOutcome()->getLearningEntity()->getUplinks();
        /** @var $uplink Uplink */
        foreach ($uplinks as $uplink) {
            /** @var $parentEntity LearningEntity */
            $parentEntity = $uplink->getParent();
            if (is_a($parentEntity,'La\CoreBundle\Entity\Agora')) {
                $downLinks = $parentEntity->getDownlinks();
                $affinityForOutcome = 0;
                $totalWeight = 0;
                /** @var $downLink Uplink */
                foreach ($downLinks as $downLink) {
                    $child = $downLink->getChild();
                    $outcomes = $child->getOutcomes();
                    $weight = $child->getContent()->getDuration() * max($downLink->getWeight(),1);
                    $lastResult = 0;
                    $lastTimestamp = 0;
                    /** @var $outcome Outcome */
                    foreach ($outcomes as $outcome) {
                        $results = $outcome->getResults();
                        /** @var $result Result */
                        foreach ($results as $result) {
                            if (is_a($result,'La\CoreBundle\Entity\AffinityResult')) {
                                $traces = $outcome->getTraces();
                                /** @var $trace Trace */
                                foreach ($traces as $trace) {
                                    if ($trace->getUser()->getId() == $userId) {
                                        $timestamp = strtotime($trace->getCreatedTime()->format('Y-m-d H:i:s'));
                                        if ($timestamp > $lastTimestamp) {
                                            $lastTimestamp = $timestamp;
                                            $lastResult = $result->getValue();
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $affinityForOutcome+= $weight*$lastResult;
                    $totalWeight+= $weight*100;
                }

                $affinityValue = $totalWeight ? 100*$affinityForOutcome/$totalWeight : 0;
                $affinityValue = $affinityValue<0 ? 0 : $affinityValue;

                $affinity = $this->affinityRepository->findOneBy(
                    array(
                        'user' => $this->securityContext->getToken()->getUser(),
                        'agora' => $parentEntity
                    )
                );
                if (!$affinity) {
                    $affinity = new Affinity();
                    $affinity->setUser($this->securityContext->getToken()->getUser());
                    $affinity->setAgora($parentEntity);
                }
                $affinity->setValue($affinityValue);
                $this->entityManager->persist($affinity);
                $this->entityManager->flush();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function visitProgressResult(ProgressResult $result)
    {
        $user = $this->securityContext->getToken()->getUser();
        $learningEntity = $result->getOutcome()->getLearningEntity();

        $progress = $this->progressRepository->findOneBy(
            array(
                'user'           => $user,
                'learningEntity' => $learningEntity
            )
        );


        if (!$progress) {
            $progress = new Progress();
            $progress->setUser($user);
            $progress->setLearningEntity($learningEntity);
        }

        $progress->setValue($result->getValue());

        $this->entityManager->persist($progress);
        $this->entityManager->flush();

        //$this->updateParents($learningEntity,$user);
        $learningEntity->accept($this);
    }
        /**
     * {@inheritdoc}
     */
    public function visitNextEntityResult(NextEntityResult $result)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function visitAction(Action $learningEntity)
    {
        $user = $this->securityContext->getToken()->getUser();

        foreach ($learningEntity->getUplinks() as $upLink)
        {
            /* @var LearningEntity $parentLearningEntity */
            $parentLearningEntity = $upLink->getParent();

            $getTypeVisitor = new GetTypeVisitor();
            $parentLearningEntityType = $parentLearningEntity->accept($getTypeVisitor);
            if ($parentLearningEntityType == 'Objective') {
                $totalProgressValue = 0;
                $totalWeight = 0;

                foreach ($parentLearningEntity->getDownlinks() as $downLink)
                {
                    $progressForChild = $this->progressRepository->findOneBy(
                        array(
                            'user'           => $user,
                            'learningEntity' => $downLink->getChild()
                        )
                    );
                    $progressValue = $progressForChild ? $progressForChild->getValue() : 0;

                    $weight = $downLink->getWeight();
                    $totalProgressValue += $progressValue * $weight;
                    $totalWeight += $weight;
                }
                $finalProgressValue = $totalWeight ? $totalProgressValue/$totalWeight : 0;

                $progressForParent = $this->progressRepository->findOneBy(
                    array(
                        'user'           => $user,
                        'learningEntity' => $parentLearningEntity
                    )
                );

                if (!$progressForParent && $finalProgressValue)
                {
                    $progressForParent = new Progress();
                    $progressForParent->setLearningEntity($parentLearningEntity);
                    $progressForParent->setUser($user);
                }

                if ($progressForParent)
                {
                    $progressForParent->setValue($finalProgressValue);
                    $this->entityManager->persist($progressForParent);
                    $this->entityManager->flush();
                }

                $parentLearningEntity->accept($this);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function visitObjective(Objective $learningEntity)
    {
        $user = $this->securityContext->getToken()->getUser();
        $getTypeVisitor = new GetTypeVisitor();

        foreach ($learningEntity->getUplinks() as $upLink)
        {
            /* @var LearningEntity $parentLearningEntity */
            $parentLearningEntity = $upLink->getParent();

            $parentLearningEntityType = $parentLearningEntity->accept($getTypeVisitor);
            if ($parentLearningEntityType == 'Agora') {
                $totalProgressValue = 0;
                $totalWeight = 0;

                foreach ($parentLearningEntity->getDownlinks() as $downLink)
                {
                    $childLearningEntity = $downLink->getChild();
                    $childLearningEntityType = $childLearningEntity->accept($getTypeVisitor);

                    if ($childLearningEntityType == 'Objective') {
                        $progressForChild = $this->progressRepository->findOneBy(
                            array(
                                'user' => $user,
                                'learningEntity' => $childLearningEntity
                            )
                        );
                        $progressValue = $progressForChild ? $progressForChild->getValue() : 0;

                        $weight = $downLink->getWeight();
                        $totalProgressValue += $progressValue * $weight;
                        $totalWeight += $weight;
                    }
                }
                $finalProgressValue = $totalWeight ? $totalProgressValue/$totalWeight : 0;

                $progressForParent = $this->progressRepository->findOneBy(
                    array(
                        'user'           => $user,
                        'learningEntity' => $parentLearningEntity
                    )
                );

                if (!$progressForParent && $finalProgressValue)
                {
                    $progressForParent = new Progress();
                    $progressForParent->setLearningEntity($parentLearningEntity);
                    $progressForParent->setUser($user);
                }

                if ($progressForParent)
                {
                    $progressForParent->setValue($finalProgressValue);
                    $this->entityManager->persist($progressForParent);
                    $this->entityManager->flush();
                }

                $parentLearningEntity->accept($this);
            }
        }
    }

}
