<?php

namespace spec\La\SecurityBundle\Command;

use FOS\OAuthServerBundle\Model\ClientManagerInterface;
use La\SecurityBundle\Entity\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateClientSpec extends ObjectBehavior
{
    function let(ClientManagerInterface $clientManager)
    {
        $this->beConstructedWith($clientManager);
    }

    function it_is_a_command()
    {
        $this->shouldHaveType('Symfony\Component\Console\Command\Command');
    }

    function it_initializes_the_base_class()
    {
        $this->getDefinition()->shouldNotBeNull();
    }

    function it_creates_a_new_client(ClientManagerInterface $clientManager, InputInterface $input, OutputInterface $output)
    {
        $client = new Client();
        $clientManager->createClient()->shouldBeCalled()->willReturn($client);
        $input->getOption(Argument::any())->willReturn(array());
        $input->bind(Argument::any())->shouldBeCalled();
        $input->validate()->shouldBeCalled();
        $input->isInteractive(Argument::any())->shouldBeCalled();
        $clientManager->updateClient($client)->shouldBeCalled();
        $this->run($input, $output);
    }
}
