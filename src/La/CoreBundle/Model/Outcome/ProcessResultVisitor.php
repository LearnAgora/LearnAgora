<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\CoreBundle\Model\Outcome;


use La\CoreBundle\Entity\Affinity;
use La\CoreBundle\Entity\AffinityResult;
use La\CoreBundle\Entity\NextEntityResult;
use La\CoreBundle\Entity\Trace;
use La\CoreBundle\Entity\Uplink;
use La\CoreBundle\Entity\LearningEntity;
use La\CoreBundle\Visitor\AffinityResultVisitorInterface;
use La\CoreBundle\Visitor\NextEntityResultVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;
use Proxies\__CG__\La\CoreBundle\Entity\Outcome;
use Proxies\__CG__\La\CoreBundle\Entity\Result;

class ProcessResultVisitor implements VisitorInterface, AffinityResultVisitorInterface, NextEntityResultVisitorInterface
{
    private $user;
    private $userId;
    private $em;

    public function __construct($user,$em)
    {
        $this->user = $user;
        $this->userId = $user->getId();
        $this->em = $em;
    }
    /**
     * {@inheritdoc}
     */
    public function visitAffinityResult(AffinityResult $result)
    {
        $uplinks = $result->getOutcome()->getLearningEntity()->getUplinks();
        /** @var $uplink Uplink */
        foreach ($uplinks as $uplink) {
            /** @var $parentEntity LearningEntity */
            $parentEntity = $uplink->getParent();
            if (is_a($parentEntity,'La\CoreBundle\Entity\Agora')) {
                $downLinks = $parentEntity->getDownlinks();
                $affinity = 0;
                $maxAffinity = 0;
                /** @var $downLink Uplink */
                foreach ($downLinks as $downLink) {
                    $maxAffinity+= 100;
                    $affinityForOutcome = 0;
                    $numTraces = 0;
                    $child = $downLink->getChild();
                    $outcomes = $child->getOutcomes();
                    /** @var $outcome Outcome */
                    foreach ($outcomes as $outcome) {
                        $results = $outcome->getResults();
                        /** @var $result Result */
                        foreach ($results as $result) {
                            if (is_a($result,'La\CoreBundle\Entity\AffinityResult')) {
                                $traces = $outcome->getTraces();
                                /** @var $trace Trace */
                                foreach ($traces as $trace) {
                                    if ($trace->getUser()->getId() == $this->userId) {
                                        $affinityForOutcome+= $result->getValue();
                                        $numTraces++;
                                    }
                                }
                            }
                        }
                    }
                    $affinity+= $numTraces ? $affinityForOutcome/$numTraces : 0;
                }
                $affinityValue = $maxAffinity ? 100*$affinity/$maxAffinity : 0;
                $affinity = $this->em->getRepository('LaCoreBundle:Affinity')->findOneBy(
                    array(
                        'user' => $this->user,
                        'agora' => $parentEntity
                    )
                );
                if (!$affinity) {
                    $affinity = new Affinity();
                    $affinity->setUser($this->user);
                    $affinity->setAgora($parentEntity);
                }
                $affinity->setValue($affinityValue);
                $this->em->persist($affinity);
                $this->em->flush();
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