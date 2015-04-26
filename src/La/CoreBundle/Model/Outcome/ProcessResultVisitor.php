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
use La\CoreBundle\Entity\AnswerOutcome;
use La\CoreBundle\Entity\ButtonOutcome;
use La\CoreBundle\Entity\Objective;
use La\CoreBundle\Entity\Outcome;
use La\CoreBundle\Entity\Progress;
use La\CoreBundle\Entity\LearningEntity;
use La\CoreBundle\Entity\UrlOutcome;
use La\CoreBundle\Model\LearningEntity\GetTypeVisitor;
use La\CoreBundle\Visitor\ButtonOutcomeVisitorInterface;
use La\CoreBundle\Visitor\AnswerOutcomeVisitorInterface;
use La\CoreBundle\Visitor\UrlOutcomeVisitorInterface;
use La\CoreBundle\Visitor\ActionVisitorInterface;
use La\CoreBundle\Visitor\ObjectiveVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;
use La\CoreBundle\Entity\Repository\TraceRepository;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * @DI\Service("la_learnodex.process_result_visitor")
 */
class ProcessResultVisitor implements
    VisitorInterface,
    ButtonOutcomeVisitorInterface,
    AnswerOutcomeVisitorInterface,
    UrlOutcomeVisitorInterface,
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
    private $progressRepository;
    /**
     * @var TraceRepository
     */
    private $traceRepository;


    /**
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext
     * @param ObjectManager $entityManager
     * @param ObjectRepository $progressRepository
     * @param TraceRepository $traceRepository
     *
     * @DI\InjectParams({
     *  "securityContext" = @DI\Inject("security.context"),
     *  "entityManager" = @DI\Inject("doctrine.orm.entity_manager"),
     *  "progressRepository" = @DI\Inject("la_core.repository.progress"),
     *  "traceRepository" = @DI\Inject("la_core.repository.trace")
     * })
     */
    public function __construct(SecurityContextInterface $securityContext, ObjectManager $entityManager, ObjectRepository $progressRepository, TraceRepository $traceRepository)
    {
        $this->securityContext = $securityContext;
        $this->entityManager = $entityManager;
        $this->progressRepository = $progressRepository;
        $this->traceRepository = $traceRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function visitButtonOutcome(ButtonOutcome $outcome)
    {
    }
    /**
     * {@inheritdoc}
     */
    public function visitAnswerOutcome(AnswerOutcome $outcome)
    {
        $this->processResult($outcome);
    }
    /**
     * {@inheritdoc}
     */
    public function visitUrlOutcome(UrlOutcome $outcome)
    {
    }

    private function processResult(Outcome $outcome)
    {
        $user = $this->securityContext->getToken()->getUser();
        /* @var LearningEntity $learningEntity */
        $learningEntity = $outcome->getLearningEntity();

        //handle Progress
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

        $progressValue = $outcome->getProgress();
        if (is_null($progressValue)) {
            $progressValue = 0;
        }
        $progress->setValue($progressValue);

        $this->entityManager->persist($progress);
        $this->entityManager->flush();

        //Cascade up
        $learningEntity->accept($this);

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
