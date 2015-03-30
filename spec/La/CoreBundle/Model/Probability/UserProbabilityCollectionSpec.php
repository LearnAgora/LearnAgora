<?php

namespace spec\La\CoreBundle\Model\Probability;

use La\CoreBundle\Entity\Profile;
use La\CoreBundle\Entity\Repository\ProfileRepository;
use La\CoreBundle\Entity\UserProbability;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class UserProbabilityCollectionSpec extends ObjectBehavior
{

    function let(EventDispatcherInterface $eventDispatcher)
    {
        $this->beConstructedWith($eventDispatcher);

        $this->shouldHaveType('La\CoreBundle\Model\Probability\UserProbabilityCollection');
    }


    function it_can_store_one_profile(Profile $p1)
    {
        $p1->getId()->willReturn(1);
        $this->setProfiles(array($p1));
        $this->getProfiles()->shouldHaveCount(1);
    }

    function it_can_store_multiple_profiles(Profile $p1, Profile $p2, Profile $p3)
    {
        $p1->getId()->willReturn(1);
        $p2->getId()->willReturn(2);
        $p3->getId()->willReturn(3);
        $this->setProfiles(array($p1,$p2,$p3));
        $this->getProfiles()->shouldHaveCount(3);
    }

    function it_can_store_one_user_probability(Profile $p1, UserProbability $up1)
    {
        $p1->getId()->willReturn(1);
        $up1->getProfile()->willReturn($p1);
        $this->setProfiles(array($p1));
        $this->setUserProbabilities(array($up1));
        $this->getUserProbabilities()->shouldHaveCount(1);
    }
    function it_can_store_multiple_user_probability(Profile $p1, Profile $p2, UserProbability $up1, UserProbability $up2)
    {
        $p1->getId()->willReturn(1);
        $p2->getId()->willReturn(2);
        $up1->getProfile()->willReturn($p1);
        $up2->getProfile()->willReturn($p2);
        $this->setProfiles(array($p1,$p2));
        $this->setUserProbabilities(array($up1,$up2));
        $this->getUserProbabilities()->shouldHaveCount(2);
    }
    function it_checks_for_missing_user_probabilities(Profile $p1, Profile $p2, UserProbability $up1)
    {
        $p1->getId()->willReturn(1);
        $p2->getId()->willReturn(2);
        $up1->getProfile()->willReturn($p1);

        $this->setProfiles(array($p1,$p2));
        $this->setUserProbabilities(array($up1));
        $this->hasMissingUserProbabilities()->shouldBe(true);
    }
    function it_checks_if_all_user_probabilities_are_set(Profile $p1, Profile $p2, UserProbability $up1, UserProbability $up2)
    {
        $p1->getId()->willReturn(1);
        $p2->getId()->willReturn(3);
        $up1->getProfile()->willReturn($p1);
        $up2->getProfile()->willReturn($p2);

        $this->setProfiles(array($p1,$p2));
        $this->setUserProbabilities(array($up1,$up2));
        $this->hasMissingUserProbabilities()->shouldBe(false);
    }
}
