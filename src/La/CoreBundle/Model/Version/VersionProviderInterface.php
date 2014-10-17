<?php

namespace La\CoreBundle\Model\Version;

interface VersionProviderInterface
{
    /**
     * @return string
     */
    public function getVersion();
}
