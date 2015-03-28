<?php

namespace La\SecurityBundle\Command;

use FOS\OAuthServerBundle\Model\ClientManagerInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This command creates a new OAuth2 Client.
 *
 * @DI\Service("la_security.command.create_client")
 * @DI\Tag("console.command")
 */
class CreateClient extends Command
{
    /**
     * @var ClientManagerInterface
     */
    private $clientManager;

    /**
     * @param ClientManagerInterface $clientManager
     *
     * @DI\InjectParams({
     *     "clientManager" = @DI\Inject("fos_oauth_server.client_manager")
     * })
     */
    public function __construct(ClientManagerInterface $clientManager)
    {
        parent::__construct(null);

        $this->clientManager = $clientManager;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('la:security:client:create')
            ->setDescription('Creates a new OAuth2 Client')
            ->addOption('redirect-uri', 'r', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Sets redirect uri for client')
            ->addOption('grant-type', 'g', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Sets allowed grant type for client')
            ->setHelp(
<<<EOH
The <info>%command.name%</info> command creates a new OAuth2 Client.

<info>php %command.full_name% [--redirect-uri=...] [--grant-type=...]</info>

EOH
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = $this->clientManager->createClient();
        $client->setRedirectUris($input->getOption('redirect-uri'));
        $client->setAllowedGrantTypes($input->getOption('grant-type'));
        $this->clientManager->updateClient($client);
        $output->writeln(sprintf('Added a new OAuth2 Client with Public Id <info>%s</info>, Secret <info>%s</info>', $client->getPublicId(), $client->getSecret()));
    }
}
