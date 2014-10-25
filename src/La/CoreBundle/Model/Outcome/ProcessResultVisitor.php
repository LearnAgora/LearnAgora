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
use La\CoreBundle\Entity\Affinity;
use La\CoreBundle\Entity\AffinityResult;
use La\CoreBundle\Entity\NextEntityResult;
use La\CoreBundle\Entity\Trace;
use La\CoreBundle\Entity\Uplink;
use La\CoreBundle\Entity\LearningEntity;
use La\CoreBundle\Visitor\AffinityResultVisitorInterface;
use La\CoreBundle\Visitor\NextEntityResultVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * @DI\Service("la_learnodex.process_result_visitor")
 */
class ProcessResultVisitor implements VisitorInterface, AffinityResultVisitorInterface, NextEntityResultVisitorInterface
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
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext
     * @param ObjectManager $entityManager
     * @param ObjectRepository $affinityRepository
     *
     * @DI\InjectParams({
     *  "securityContext" = @DI\Inject("security.context"),
     *  "entityManager" = @DI\Inject("doctrine.orm.entity_manager"),
     *  "affinityRepository" = @DI\Inject("la_core.repository.affinity")
     * })
     */
    public function __construct(SecurityContextInterface $securityContext, ObjectManager $entityManager, ObjectRepository $affinityRepository)
    {
        $this->securityContext = $securityContext;
        $this->entityManager = $entityManager;
        $this->affinityRepository = $affinityRepository;
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
    public function visitNextEntityResult(NextEntityResult $result)
    {
    }
}
