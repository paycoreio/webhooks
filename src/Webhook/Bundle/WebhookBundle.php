<?php

namespace Webhook\Bundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class WebhookBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $mappings = [
            realpath(__DIR__ . '/Resources/config/doctrine-mapping/') => 'Webhook\\Domain\\Model',
        ];

        $container->addCompilerPass(DoctrineOrmMappingsPass::createXmlMappingDriver($mappings));
    }
}