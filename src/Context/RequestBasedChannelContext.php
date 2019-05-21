<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Context;

use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\ShopApiPlugin\Metadata\Resource\OperationCheckerTrait;
use Symfony\Component\HttpFoundation\RequestStack;

final class RequestBasedChannelContext implements ChannelContextInterface
{
    use OperationCheckerTrait;

    private $requestStack;
    private $channelRepository;

    public function __construct(RequestStack $requestStack, ResourceMetadataFactoryInterface $resourceMetadataFactory, ChannelRepositoryInterface $channelRepository)
    {
        $this->requestStack = $requestStack;
        $this->resourceMetadataFactory = $resourceMetadataFactory;
        $this->channelRepository = $channelRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getChannel(): ChannelInterface
    {
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            throw new ChannelNotFoundException('Request not available.');
        }

        if (!$this->isSyliusShopApiOperation($request)) {
            throw new ChannelNotFoundException('Not handling a ShopApiPlugin operation.');
        }

        if (null === $channelCode = $request->attributes->get('channelCode')) {
            throw new ChannelNotFoundException('The channelCode parameter is required in path.');
        }

        $channel = $this->channelRepository->findOneByCode($channelCode);
        if (null === $channel) {
            throw new ChannelNotFoundException(sprintf('Channel with code %s has not been found.', $channelCode));
        }

        return $channel;
    }
}
