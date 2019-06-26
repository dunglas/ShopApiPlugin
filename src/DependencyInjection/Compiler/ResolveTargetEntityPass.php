<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @see https://github.com/Sylius/Sylius/issues/10408
 */
final class ResolveTargetEntityPass implements CompilerPassInterface
{
    private const RESOURCE_INTERFACES = [
        \Sylius\Component\Core\Model\ChannelInterface::class => 'sylius.model.channel.class',
        \Sylius\Component\Core\Model\CustomerInterface::class => 'sylius.model.customer.class',
        \Sylius\Component\Core\Model\ProductInterface::class => 'sylius.model.product.class',
        \Sylius\Component\Core\Model\ProductReviewerInterface::class => 'sylius.model.product_reviewer.class',
        \Sylius\Component\Core\Model\TaxonInterface::class => 'sylius.model.taxon.class',
    ];

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('doctrine.orm.listeners.resolve_target_entity')) {
            return;
        }

        $definition = $container->findDefinition('doctrine.orm.listeners.resolve_target_entity');

        foreach (self::RESOURCE_INTERFACES as $interface => $classParameter) {
            $definition->addMethodCall('addResolveTargetEntity', [
                $interface,
                $container->getParameter($classParameter),
                [],
            ]);
        }
    }
}
