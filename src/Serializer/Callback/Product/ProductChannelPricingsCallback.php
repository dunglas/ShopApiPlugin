<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Serializer\Callback\Product;

use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\ShopApiPlugin\Serializer\ContextKeys;
use Webmozart\Assert\Assert;

final class ProductChannelPricingsCallback
{
    public function __invoke($attributeValue, $object, $attribute, $format, $context): iterable
    {
        if (!$object instanceof ProductVariantInterface) {
            return $attributeValue;
        }

        $productVariant = $object;

        Assert::keyExists($context, ContextKeys::CHANNEL);

        $channel = $context[ContextKeys::CHANNEL];

        Assert::isInstanceOf($channel, ChannelInterface::class);

        $channelPricing = $productVariant->getChannelPricingForChannel($channel);

        Assert::notNull($channelPricing);

        return [$channelPricing];
    }
}
