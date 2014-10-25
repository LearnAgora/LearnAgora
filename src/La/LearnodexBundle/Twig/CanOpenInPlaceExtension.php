<?php

namespace La\LearnodexBundle\Twig;

use GuzzleHttp\Client;
use JMS\DiExtraBundle\Annotation as DI;
use Twig_Extension;
use Twig_SimpleFilter;

/**
 * @DI\Service("la_learnodex.twig_extension.can_open_in_place")
 * @DI\Tag("twig.extension")
 */
class CanOpenInPlaceExtension extends Twig_Extension
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     *
     * @DI\InjectParams({
     *  "client" = @DI\Inject("la_learnodex.third_party.guzzle_client")
     * })
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Check if the given URL can be opened inside of an iframe.
     *
     * @param string $url
     *
     * @return boolean
     */
    public function canOpenInPlaceFilter($url)
    {
        $response = $this->client->head($url);
        $header = strtolower($response->getHeader('x-frame-options'));

        if (in_array($header, array("deny", "sameorigin", "allow-from"))) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new Twig_SimpleFilter('canOpenInPlace', array($this, 'canOpenInPlaceFilter')),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'la_learnodex_can_open_in_place_extension';
    }
}
