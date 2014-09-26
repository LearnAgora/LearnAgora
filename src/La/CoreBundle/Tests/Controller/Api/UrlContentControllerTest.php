<?php

namespace La\CoreBundle\Tests\Controller\Api;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use La\CoreBundle\Controller\Api\UrlContentController;
use Prophecy\PhpUnit\ProphecyTestCase;
use stdClass;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class UrlContentControllerTest extends ProphecyTestCase
{
    /**
     * @var ObjectRepository
     */
    private $urlContentRepository;

    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * @var FormInterface
     */
    private $formFactory;

    /**
     * @var UrlContentController
     */
    private $sut;

    /**
     * @var RequestStack
     */
    private $requestStack;

    protected function setUp()
    {
        parent::setUp();

        $this->urlContentRepository = $this->prophesize('\Doctrine\Common\Persistence\ObjectRepository');
        $this->entityManager = $this->prophesize('\Doctrine\Common\Persistence\ObjectManager');
        $this->formFactory = $this->prophesize('\Symfony\Component\Form\FormFactoryInterface');
        $this->requestStack = $this->prophesize('\Symfony\Component\HttpFoundation\RequestStack');
        $this->sut = new UrlContentController($this->urlContentRepository->reveal(), $this->entityManager->reveal(), $this->formFactory->reveal(), $this->requestStack->reveal());
    }

    /** @test */
    public function it_gets_a_single_resource()
    {
        $content = new stdClass();
        $this->urlContentRepository->find(1)->shouldBeCalled()->willReturn($content);

        $this->assertSame($content, $this->sut->getAction(1));
    }

    /**
     * @test
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @expectedExceptionMessage UrlContent resource with id "1" not found.
     */
    public function it_throws_exception_if_no_random_card_is_found()
    {
        $this->urlContentRepository->find(1)->shouldBeCalled()->willReturn(null);

        $this->sut->getAction(1);
    }

    /** @test */
    public function it_gets_multiple_resources()
    {
        $contents = array(new stdClass(), new stdClass());
        $this->urlContentRepository->findAll()->shouldBeCalled()->willReturn($contents);

        $this->assertSame($contents, $this->sut->cgetAction());
    }

    /** @test */
    public function it_returns_empty_when_no_resources_are_found()
    {
        $contents = array();
        $this->urlContentRepository->findAll()->shouldBeCalled()->willReturn($contents);

        $this->assertSame($contents, $this->sut->cgetAction());
    }
}
