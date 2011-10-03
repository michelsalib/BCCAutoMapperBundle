<?php

namespace BCC\AutoMapperBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use BCC\AutoMapperBundle\DependencyInjection\Compiler\MapPass;

/**
 * BCCAutoMapperBundle.
 *
 * @author Michel Salib <michelsalib@hotmail.com>
 */
class BCCAutoMapperBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        
        $container->addCompilerPass(new MapPass());
    }
}
