<?php

namespace test\La\LearnodexBundle\Tests\Model;

use Doctrine\Common\Persistence\ObjectRepository;
use La\LearnodexBundle\Model\NaiveRandomCardProvider;
use La\LearnodexBundle\Model\NonRandomRandomCardProvider;
use Prophecy\PhpUnit\ProphecyTestCase;

class NonRandomRandomCardProviderTest extends ProphecyTestCase
{
    const ID = 1;

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
        $this->sut = new NonRandomRandomCardProvider($this->learningEntityRepository->reveal(), self::ID);
    }

    /** @test */
    public function it_is_a_random_card_provider()
    {
        $this->assertInstanceOf('\La\LearnodexBundle\Model\RandomCardProviderInterface', $this->sut);
    }

    /** @test */
    public function it_returns_always_the_same_card()
    {
        $learningEntity = $this->prophesize('\La\CoreBundle\Entity\LearningEntity')->reveal();
        $this->learningEntityRepository->find(self::ID)->shouldBeCalled()->willReturn($learningEntity);

        $this->assertInstanceOf('\La\LearnodexBundle\Model\Card', $this->sut->getCard());
        $this->assertSame($this->sut->getCard()->getLearningEntity(), $learningEntity);
    }

    /**
     * @test
     * @expectedException \La\LearnodexBundle\Model\Exception\CardNotFoundException
     * @expectedExceptionMessage Could not find requested card.
     */
    public function it_throws_exception_if_no_card_is_found()
    {
        $this->learningEntityRepository->find(self::ID)->shouldBeCalled()->willReturn(null);

        $this->sut->getCard();
    }
}
