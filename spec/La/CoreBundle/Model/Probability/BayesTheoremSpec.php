<?php

namespace spec\La\CoreBundle\Model\Probability;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BayesTheoremSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('La\CoreBundle\Model\Probability\BayesTheorem');
    }


}
