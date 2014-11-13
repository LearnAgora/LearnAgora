<?php

namespace La\CoreBundle\Twig;

use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Model\Version\VersionProviderInterface;
use Twig_Extension;
use Twig_SimpleFunction;

/**
 * @DI\Service("la_core.twig_extension.version")
 * @DI\Tag("twig.extension")
 */
class VersionExtension extends Twig_Extension
{
    /**
     * @var VersionProviderInterface
     */
    private $provider;

    /**
     * @param VersionProviderInterface $provider
     *
     * @DI\InjectParams({
     *  "provider" = @DI\Inject("version_provider")
     * })
     */
    public function __construct(VersionProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return string
     */
    public function versionAsString()
    {
        return $this->provider->getVersion();
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('version', array($this, 'versionAsString')),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'la_core_twig_version_extension';
    }
}
