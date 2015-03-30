<?php

namespace spec\La\CoreBundle\Entity\Repository;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProfileRepositorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('La\CoreBundle\Entity\Repository\ProfileRepository');
    }
}
