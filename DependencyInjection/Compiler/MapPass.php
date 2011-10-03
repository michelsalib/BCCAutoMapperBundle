<?php

namespace BCC\AutoMapperBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * MapPass registers the tagged maps with the mapper.
 * 
 * @author Michel Salib <michelsalib@hotmail.com>
 */
class MapPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {        
        if ($container->hasDefinition('bcc_auto_mapper.mapper')) {
            $definition = $container->getDefinition('bcc_auto_mapper.mapper');
            foreach ($container->findTaggedServiceIds('bcc_auto_mapper.map') as $id => $attributes) {
                $definition->addMethodCall('registerMap', array(new Reference($id)));
            }
        }
    }
}
