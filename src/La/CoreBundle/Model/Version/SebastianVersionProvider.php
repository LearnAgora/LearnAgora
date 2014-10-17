<?php

namespace La\CoreBundle\Model\Version;

use JMS\DiExtraBundle\Annotation as DI;
use SebastianBergmann\Version;

/**
 * @DI\Service("la_core.sebastian_version_provider")
 */
class SebastianVersionProvider implements VersionProviderInterface
{
    /**
     * @var Version
     */
    private $sebastian;

    /**
     * Constructor
     *
     * @param Version $sebastian
     *
     * @DI\InjectParams({
     *  "sebastian" = @DI\Inject("la_core.third_party.sebastian_version")
     * })
     */
    public function __construct(Version $sebastian)
    {
        $this->sebastian = $sebastian;
    }

    /**
     * {@inheritdoc}
     */
    public function getVersion()
    {
        return $this->sebastian->getVersion();
    }
}
