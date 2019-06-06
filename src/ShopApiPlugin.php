<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Sylius\ShopApiPlugin\DependencyInjection\Compiler\ResolveTargetEntityPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class ShopApiPlugin extends Bundle
{
    use SyliusPluginTrait;

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ResolveTargetEntityPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 1);
    }
}
