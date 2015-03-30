<?php

namespace spec\La\CoreBundle\Model\Probability;

use La\CoreBundle\Entity\OutcomeProbability;
use La\CoreBundle\Entity\Profile;
use La\CoreBundle\Entity\UserProbability;
use La\CoreBundle\Model\Probability\OutcomeProbabilityCollection;
use La\CoreBundle\Model\Probability\UserProbabilityCollection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BayesTheoremSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('La\CoreBundle\Model\Probability\BayesTheorem');
    }

    function it_calculates_bayes_theorem(UserProbabilityCollection $upc, OutcomeProbabilityCollection $opc, Profile $p1, Profile $p2, UserProbability $up1, UserProbability $up2, OutcomeProbability $op1, OutcomeProbability $op2)
    {
        $up1->getProfile()->willReturn($p1);
        $up1->getProbability()->willReturn(0.4);
        $up2->getProfile()->willReturn($p2);
        $up2->getProbability()->willReturn(0.6);

        $op1->getProfile()->willReturn($p1);
        $op1->getProbability()->willReturn(5);
        $op2->getProfile()->willReturn($p2);
        $op2->getProbability()->willReturn(50);

        $upc->getProfiles()->willReturn(array($p1,$p2));
        $upc->getUserProbabilityForProfile($p1)->willReturn($up1);
        $upc->getUserProbabilityForProfile($p2)->willReturn($up2);

        $opc->getProfiles()->willReturn(array($p1,$p2));
        $opc->getOutcomeProbabilityForProfile($p1)->willReturn($op1);
        $opc->getOutcomeProbabilityForProfile($p2)->willReturn($op2);

        $up1->setProbability(0.0625)->shouldBeCalled();
        $up2->setProbability(0.9375)->shouldBeCalled();
        $upc->setUserProbabilityForProfile($p1,$up1)->shouldBeCalled();
        $upc->setUserProbabilityForProfile($p2,$up2)->shouldBeCalled();

        $this->applyTo($upc,$opc);

    }

}
