<?php

namespace La\CoreBundle\Tests\Controller\Api;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use La\CoreBundle\Controller\Api\UrlContentController;
use La\CoreBundle\Entity\UrlContent;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTestCase;
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
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var UrlContentController
     */
    private $sut;

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
        $content = new UrlContent();
        $this->urlContentRepository->find(1)->shouldBeCalled()->willReturn($content);

        $this->assertSame($content, $this->sut->getAction(1));
    }

    /**
     * @test
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @expectedExceptionMessage UrlContent resource with id "1" not found.
     */
    public function it_throws_exception_if_no_resource_is_found()
    {
        $this->urlContentRepository->find(1)->shouldBeCalled()->willReturn(null);

        $this->sut->getAction(1);
    }

    /** @test */
    public function it_gets_multiple_resources()
    {
        $contents = array(new UrlContent(), new UrlContent());
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

    /** @test */
    public function it_accepts_a_new_resource()
    {
        $data = 'just some data...';

        $request = $this->prophesize('\Symfony\Component\HttpFoundation\Request');
        $request->get('form_url_content')->shouldBeCalled()->willReturn($data);

        $this->requestStack->getCurrentRequest()->willReturn($request->reveal());

        $form = $this->prophesize('\Symfony\Component\Form\FormInterface');
        $form->submit($data, true)->shouldBeCalled();
        $form->isValid()->shouldBeCalled()->willReturn(true);

        $this->formFactory->create('form_url_content', Argument::type('\La\CoreBundle\Entity\UrlContent'))->shouldBeCalled()->willReturn($form->reveal());

        $this->entityManager->persist(Argument::type('\La\CoreBundle\Entity\UrlContent'))->shouldBeCalled();
        $this->entityManager->flush()->shouldBeCalled();

        $view = $this->sut->postAction();

        $this->assertInstanceOf('\FOS\RestBundle\View\View', $view);
        $this->assertEquals(201, $view->getStatusCode());
        $this->assertInstanceOf('\La\CoreBundle\Entity\UrlContent', $view->getData());
    }

    /** @test */
    public function it_validates_a_post_request()
    {
        $data = 'just some data...';

        $request = $this->prophesize('\Symfony\Component\HttpFoundation\Request');
        $request->get('form_url_content')->shouldBeCalled()->willReturn($data);

        $this->requestStack->getCurrentRequest()->willReturn($request->reveal());

        $form = $this->prophesize('\Symfony\Component\Form\FormInterface');
        $form->submit($data, true)->shouldBeCalled();
        $form->isValid()->shouldBeCalled()->willReturn(false);

        $this->formFactory->create('form_url_content', Argument::type('\La\CoreBundle\Entity\UrlContent'))->shouldBeCalled()->willReturn($form->reveal());

        $this->entityManager->persist(Argument::any())->shouldNotBeCalled();
        $this->entityManager->flush()->shouldNotBeCalled();

        $view = $this->sut->postAction();

        $this->assertInstanceOf('\FOS\RestBundle\View\View', $view);
        $this->assertEquals(422, $view->getStatusCode());
        $this->assertInstanceOf('\Symfony\Component\Form\FormInterface', $view->getData());
    }

    /** @test */
    public function it_does_not_accept_invalid_post_requests()
    {
        $this->requestStack->getCurrentRequest()->willReturn(null);

        $this->entityManager->persist(Argument::any())->shouldNotBeCalled();
        $this->entityManager->flush()->shouldNotBeCalled();

        $view = $this->sut->postAction();

        $this->assertInstanceOf('\FOS\RestBundle\View\View', $view);
        $this->assertEquals(400, $view->getStatusCode());
        $this->assertNull($view->getData());
    }

    /** @test */
    public function it_updates_an_existing_resource()
    {
        $content = new UrlContent();

        $this->urlContentRepository->find(1)->shouldBeCalled()->willReturn($content);

        $data = 'just some data...';

        $request = $this->prophesize('\Symfony\Component\HttpFoundation\Request');
        $request->get('form_url_content')->shouldBeCalled()->willReturn($data);

        $this->requestStack->getCurrentRequest()->willReturn($request->reveal());

        $form = $this->prophesize('\Symfony\Component\Form\FormInterface');
        $form->submit($data, true)->shouldBeCalled();
        $form->isValid()->shouldBeCalled()->willReturn(true);

        $this->formFactory->create('form_url_content', $content)->shouldBeCalled()->willReturn($form->reveal());

        $this->entityManager->persist($content)->shouldBeCalled();
        $this->entityManager->flush()->shouldBeCalled();

        $view = $this->sut->putAction(1);

        $this->assertInstanceOf('\FOS\RestBundle\View\View', $view);
        $this->assertEquals(200, $view->getStatusCode());
        $this->assertInstanceOf('\La\CoreBundle\Entity\UrlContent', $view->getData());
    }

    /** @test */
    public function it_validates_a_put_request()
    {
        $content = new UrlContent();

        $this->urlContentRepository->find(1)->shouldBeCalled()->willReturn($content);

        $data = 'just some data...';

        $request = $this->prophesize('\Symfony\Component\HttpFoundation\Request');
        $request->get('form_url_content')->shouldBeCalled()->willReturn($data);

        $this->requestStack->getCurrentRequest()->willReturn($request->reveal());

        $form = $this->prophesize('\Symfony\Component\Form\FormInterface');
        $form->submit($data, true)->shouldBeCalled();
        $form->isValid()->shouldBeCalled()->willReturn(false);

        $this->formFactory->create('form_url_content', $content)->shouldBeCalled()->willReturn($form->reveal());

        $this->entityManager->persist(Argument::any())->shouldNotBeCalled();
        $this->entityManager->flush()->shouldNotBeCalled();

        $view = $this->sut->putAction(1);

        $this->assertInstanceOf('\FOS\RestBundle\View\View', $view);
        $this->assertEquals(422, $view->getStatusCode());
        $this->assertInstanceOf('\Symfony\Component\Form\FormInterface', $view->getData());
    }

    /** @test */
    public function it_does_not_accept_invalid_put_requests()
    {
        $this->urlContentRepository->find(1)->shouldBeCalled()->willReturn(new UrlContent());

        $this->requestStack->getCurrentRequest()->willReturn(null);

        $this->entityManager->persist(Argument::any())->shouldNotBeCalled();
        $this->entityManager->flush()->shouldNotBeCalled();

        $view = $this->sut->putAction(1);

        $this->assertInstanceOf('\FOS\RestBundle\View\View', $view);
        $this->assertEquals(400, $view->getStatusCode());
        $this->assertNull($view->getData());
    }

    /** @test */
    public function it_partially_updates_an_existing_resource()
    {
        $content = new UrlContent();

        $this->urlContentRepository->find(1)->shouldBeCalled()->willReturn($content);

        $data = 'just some data...';

        $request = $this->prophesize('\Symfony\Component\HttpFoundation\Request');
        $request->get('form_url_content')->shouldBeCalled()->willReturn($data);

        $this->requestStack->getCurrentRequest()->willReturn($request->reveal());

        $form = $this->prophesize('\Symfony\Component\Form\FormInterface');
        $form->submit($data, false)->shouldBeCalled();
        $form->isValid()->shouldBeCalled()->willReturn(true);

        $this->formFactory->create('form_url_content', $content)->shouldBeCalled()->willReturn($form->reveal());

        $this->entityManager->persist($content)->shouldBeCalled();
        $this->entityManager->flush()->shouldBeCalled();

        $view = $this->sut->patchAction(1);

        $this->assertInstanceOf('\FOS\RestBundle\View\View', $view);
        $this->assertEquals(200, $view->getStatusCode());
        $this->assertInstanceOf('\La\CoreBundle\Entity\UrlContent', $view->getData());
    }

    /** @test */
    public function it_validates_a_patch_request()
    {
        $content = new UrlContent();

        $this->urlContentRepository->find(1)->shouldBeCalled()->willReturn($content);

        $data = 'just some data...';

        $request = $this->prophesize('\Symfony\Component\HttpFoundation\Request');
        $request->get('form_url_content')->shouldBeCalled()->willReturn($data);

        $this->requestStack->getCurrentRequest()->willReturn($request->reveal());

        $form = $this->prophesize('\Symfony\Component\Form\FormInterface');
        $form->submit($data, false)->shouldBeCalled();
        $form->isValid()->shouldBeCalled()->willReturn(false);

        $this->formFactory->create('form_url_content', $content)->shouldBeCalled()->willReturn($form->reveal());

        $this->entityManager->persist(Argument::any())->shouldNotBeCalled();
        $this->entityManager->flush()->shouldNotBeCalled();

        $view = $this->sut->patchAction(1);

        $this->assertInstanceOf('\FOS\RestBundle\View\View', $view);
        $this->assertEquals(422, $view->getStatusCode());
        $this->assertInstanceOf('\Symfony\Component\Form\FormInterface', $view->getData());
    }

    /** @test */
    public function it_does_not_accept_invalid_patch_requests()
    {
        $this->urlContentRepository->find(1)->shouldBeCalled()->willReturn(new UrlContent());

        $this->requestStack->getCurrentRequest()->willReturn(null);

        $this->entityManager->persist(Argument::any())->shouldNotBeCalled();
        $this->entityManager->flush()->shouldNotBeCalled();

        $view = $this->sut->patchAction(1);

        $this->assertInstanceOf('\FOS\RestBundle\View\View', $view);
        $this->assertEquals(400, $view->getStatusCode());
        $this->assertNull($view->getData());
    }

    /** @test */
    public function it_deletes_a_resource()
    {
        $content = new UrlContent();

        $this->urlContentRepository->find(1)->shouldBeCalled()->willReturn($content);

        $this->entityManager->remove($content)->shouldBeCalled();
        $this->entityManager->flush()->shouldBeCalled();

        $view = $this->sut->deleteAction(1);

        $this->assertInstanceOf('\FOS\RestBundle\View\View', $view);
        $this->assertEquals(204, $view->getStatusCode());
        $this->assertNull($view->getData());
    }
}
