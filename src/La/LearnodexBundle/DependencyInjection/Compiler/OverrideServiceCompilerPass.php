<?php

namespace La\LearnodexBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OverrideServiceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition($container->getParameter('la_learnodex.random_card_provider'));
        $container->setDefinition('random_card_provider', $definition);
    }
}
