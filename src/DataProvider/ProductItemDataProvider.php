<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use Sylius\Component\Core\Model\ProductInterface;

final class ProductItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return is_a($resourceClass, ProductInterface::class, true);
    }

    /**
     * {@inheritdoc}
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?object
    {
        switch ($operationName) {
            case 'get_by_code':
                throw new \LogicException('To be implemented.');
            case 'get_by_slug':
                throw new \LogicException('To be implemented.');
            default:
                return null;
        }
    }
}
