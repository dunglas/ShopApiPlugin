<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\EventListener;

use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\ShopApiPlugin\Metadata\Resource\OperationCheckerTrait;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ChannelListener
{
    use OperationCheckerTrait;

    private $channelContext;

    public function __construct(ResourceMetadataFactoryInterface $resourceMetadataFactory, ChannelContextInterface $channelContext)
    {
        $this->resourceMetadataFactory = $resourceMetadataFactory;
        $this->channelContext = $channelContext;
    }

    /**
     * @throws \LogicException
     * @throws NotFoundHttpException
     */
    public function onKernelRequest(GetResponseEvent $event): void
    {
        $request = $event->getRequest();

        if (!$this->isSyliusShopApiOperation($request)) {
            return;
        }

        if (null === $channelCode = $request->attributes->get('channelCode')) {
            throw new \LogicException('The channelCode parameter is required in path.');
        }

        $channel = $this->channelContext->getChannel();
        if ($channel->getCode() !== $channelCode) {
            throw new NotFoundHttpException(sprintf('Channel with code %s has not been found.', $channelCode));
        }
    }
}
