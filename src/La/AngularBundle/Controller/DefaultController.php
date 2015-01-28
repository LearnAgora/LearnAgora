<?php

namespace La\AngularBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @var EngineInterface
     */
    private $twig;

    /**
     * Constructor.
     *
     * @param EngineInterface $twig
     *
     * @DI\InjectParams({
     *  "twig" = @DI\Inject("templating")
     * })
     */
    public function __construct(EngineInterface $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Renders the initial card UI.
     *
     * @return Response
     */
    public function cardAction()
    {
        return $this->twig->renderResponse('LaAngularBundle:Default:card.html.twig');
    }
}
