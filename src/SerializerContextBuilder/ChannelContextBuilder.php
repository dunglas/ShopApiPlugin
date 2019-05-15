<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\SerializerContextBuilder;

use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\ShopApiPlugin\Serializer\ContextKeys;
use Symfony\Component\HttpFoundation\Request;

final class ChannelContextBuilder implements SerializerContextBuilderInterface
{
    private $decorated;
    private $channelContext;

    public function __construct(SerializerContextBuilderInterface $decorated, ChannelContextInterface $channelContext)
    {
        $this->decorated = $decorated;
        $this->channelContext = $channelContext;
    }

    /**
     * {@inheritdoc}
     */
    public function createFromRequest(Request $request, bool $normalization, ?array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);

        $context[ContextKeys::CHANNEL] = $this->channelContext->getChannel();

        return $context;
    }
}
