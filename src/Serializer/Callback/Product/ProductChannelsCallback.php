<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Serializer\Callback\Product;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\ShopApiPlugin\Serializer\ContextKeys;
use Webmozart\Assert\Assert;

final class ProductChannelsCallback
{
    public function __invoke($attributeValue, $object, $attribute, $format, $context): iterable
    {
        if (!$object instanceof ProductInterface) {
            return $attributeValue;
        }

        $product = $object;

        Assert::keyExists($context, ContextKeys::CHANNEL);

        $channel = $context[ContextKeys::CHANNEL];

        Assert::isInstanceOf($channel, ChannelInterface::class);

        return $product->getChannels()->filter(function (ChannelInterface $channelElement) use ($channel): bool {
            return $channelElement->getCode() === $channel->getCode();
        })->getValues();
    }
}
