<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Metadata\Resource;

use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;
use ApiPlatform\Core\Util\RequestAttributesExtractor;
use Symfony\Component\HttpFoundation\Request;

trait OperationCheckerTrait
{
    /** @var ResourceMetadataFactoryInterface */
    private $resourceMetadataFactory;

    private function isSyliusShopApiOperation(Request $request): bool
    {
        if (!$attributes = RequestAttributesExtractor::extractAttributes($request)) {
            return false;
        }

        $resourceMetadata = $this->resourceMetadataFactory->create($attributes['resource_class']);

        return false !== $resourceMetadata->getOperationAttribute($attributes, AttributeKeys::SYLIUS_SHOP_API, false, true);
    }
}
