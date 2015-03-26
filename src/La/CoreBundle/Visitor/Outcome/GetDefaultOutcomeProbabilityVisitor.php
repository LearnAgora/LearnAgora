<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\CoreBundle\Visitor\Outcome;

use La\CoreBundle\Entity\AffinityOutcome;
use La\CoreBundle\Entity\AnswerOutcome;
use La\CoreBundle\Entity\ButtonOutcome;
use La\CoreBundle\Entity\Profile;
use La\CoreBundle\Entity\UrlOutcome;
use La\CoreBundle\Visitor\AffinityOutcomeVisitorInterface;
use La\CoreBundle\Visitor\AnswerOutcomeVisitorInterface;
use La\CoreBundle\Visitor\ButtonOutcomeVisitorInterface;
use La\CoreBundle\Visitor\UrlOutcomeVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;


class GetDefaultOutcomeProbabilityVisitor implements
    VisitorInterface,
    AffinityOutcomeVisitorInterface,
    AnswerOutcomeVisitorInterface,
    ButtonOutcomeVisitorInterface,
    UrlOutcomeVisitorInterface
{
    private $profile = null;
    private $numAnswerOutcomes = 1;
    private $probability = 0;

    public function __construct(Profile $profile, $numAnswerOutcomes)
    {
        $this->profile = $profile;
        $this->numAnswerOutcomes = $numAnswerOutcomes;
    }

    /**
     * {@inheritdoc}
     */
    public function visitAffinityOutcome(AffinityOutcome $outcome)
    {
        return $this->probability;
    }

    /**
     * {@inheritdoc}
     */
    public function visitAnswerOutcome(AnswerOutcome $outcome)
    {
        if ($outcome->getAffinity() == 100) {
            switch ($this->profile->getId()) {
                case 1 : $this->probability = 60; break;
                case 2 : $this->probability = 30; break;
                case 3 : $this->probability = 40/$this->numAnswerOutcomes; break;
                case 4 : $this->probability = 80/$this->numAnswerOutcomes; break;
                case 5 : $this->probability = 40/$this->numAnswerOutcomes; break;
            }
        } else {
            switch ($this->profile->getId()) {
                case 1 : $this->probability = 20/($this->numAnswerOutcomes-1); break;
                case 2 : $this->probability = 30/($this->numAnswerOutcomes-1); break;
                case 3 : $this->probability = 40/$this->numAnswerOutcomes; break;
                case 4 : $this->probability = 80/$this->numAnswerOutcomes; break;
                case 5 : $this->probability = 40/$this->numAnswerOutcomes; break;
            }
        }

        return $this->probability;

    }

    /**
     * {@inheritdoc}
     */
    public function visitButtonOutcome(ButtonOutcome $outcome)
    {
        if ($outcome->getCaption() == 'LATER') {
            switch ($this->profile->getId()) {
                case 1 : $this->probability = 10; break;
                case 2 : $this->probability = 20; break;
                case 3 : $this->probability = 20; break;
                case 4 : $this->probability = 5; break;
                case 5 : $this->probability = 5; break;
            }
        } elseif ($outcome->getCaption() == 'DISCARD') {
            switch ($this->profile->getId()) {
                case 1 : $this->probability = 5; break;
                case 2 : $this->probability = 5; break;
                case 3 : $this->probability = 5; break;
                case 4 : $this->probability = 10; break;
                case 5 : $this->probability = 50; break;
            }
        }

        return $this->probability;
    }

    /**
     * {@inheritdoc}
     */
    public function visitUrlOutcome(UrlOutcome $outcome)
    {
        switch ($this->profile->getId()) {
            case 1 : $this->probability = 5; break;
            case 2 : $this->probability = 35; break;
            case 3 : $this->probability = 35; break;
            case 4 : $this->probability = 5; break;
            case 5 : $this->probability = 5; break;
        }
        return $this->probability;
    }
}
