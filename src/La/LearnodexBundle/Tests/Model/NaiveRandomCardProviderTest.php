<?php

namespace La\LearnodexBundle\Tests\Model;

use Doctrine\Common\Persistence\ObjectRepository;
use La\LearnodexBundle\Model\NaiveRandomCardProvider;
use Prophecy\PhpUnit\ProphecyTestCase;

class NaiveRandomCardProviderTest extends ProphecyTestCase
{
    /**
     * @var ObjectRepository
     */
    private $learningEntityRepository;

    /**
     * @var NaiveRandomCardProvider
     */
    private $sut;

    protected function setUp()
    {
        parent::setUp();

        $this->learningEntityRepository = $this->prophesize('\Doctrine\Common\Persistence\ObjectRepository');
        $this->sut = new NaiveRandomCardProvider($this->learningEntityRepository->reveal());
    }

    /** @test */
    public function it_is_a_random_card_provider()
    {
        $this->assertInstanceOf('\La\LearnodexBundle\Model\RandomCardProviderInterface', $this->sut);
    }

    /** @test */
    public function it_returns_a_card()
    {
        $learningEntities = array(
            $this->prophesize('\La\CoreBundle\Entity\LearningEntity')->reveal(),
        );

        $this->learningEntityRepository->findAll()->shouldBeCalled()->willReturn($learningEntities);

        $this->assertInstanceOf('\La\LearnodexBundle\Model\Card', $this->sut->get());
    }

    /** @test */
    public function it_returns_one_of_the_available_cards()
    {
        $learningEntities = array(
            $this->prophesize('\La\CoreBundle\Entity\LearningEntity')->reveal(),
            $this->prophesize('\La\CoreBundle\Entity\LearningEntity')->reveal(),
            $this->prophesize('\La\CoreBundle\Entity\LearningEntity')->reveal(),
        );

        $this->learningEntityRepository->findAll()->shouldBeCalled()->willReturn($learningEntities);

        $this->assertTrue(in_array($this->sut->get()->getLearningEntity(), $learningEntities));
    }

    /**
     * @test
     * @expectedException \La\LearnodexBundle\Model\Exception\CardNotFoundException
     * @expectedExceptionMessage Could not find requested card.
     */
    public function it_throws_exception_if_no_card_is_found()
    {
        $this->learningEntityRepository->findAll()->shouldBeCalled()->willReturn(array());

        $this->sut->get();
    }
}
