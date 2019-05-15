<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\SerializerContextBuilder;

use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Symfony\Component\HttpFoundation\Request;

final class ProductContextBuilder implements SerializerContextBuilderInterface
{
    private $decorated;
    private $productAttributesCallback;
    private $productChannelsCallback;
    private $productChannelPricingsCallback;

    public function __construct(SerializerContextBuilderInterface $decorated, callable $productAttributesCallback, callable $productChannelsCallback, callable $productChannelPricingsCallback)
    {
        $this->decorated = $decorated;
        $this->productAttributesCallback = $productAttributesCallback;
        $this->productChannelsCallback = $productChannelsCallback;
        $this->productChannelPricingsCallback = $productChannelPricingsCallback;
    }

    /**
     * {@inheritdoc}
     */
    public function createFromRequest(Request $request, bool $normalization, ?array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);

        if (!$normalization || !is_a($resourceClass = $context['resource_class'] ?? null, ProductInterface::class, true)) {
            return $context;
        }

        $context['callbacks'] = [
            'attributes' => $this->productAttributesCallback,
            'channels' => $this->productChannelsCallback,
            'channelPricings' => $this->productChannelPricingsCallback,
        ];

        return $context;
    }
}
