<?php

declare(strict_types=1);

namespace spec\Sylius\ShopApiPlugin\EventListener;

use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;
use ApiPlatform\Core\Metadata\Resource\ResourceMetadata;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Model\ChannelInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ChannelListenerSpec extends ObjectBehavior
{
    function let(ResourceMetadataFactoryInterface $resourceMetadataFactory, ChannelContextInterface $channelContext): void
    {
        $this->beConstructedWith($resourceMetadataFactory, $channelContext);
    }

    function it_ensures_that_channel_code_passed_in_request_is_valid(
        ResourceMetadataFactoryInterface $resourceMetadataFactory,
        ChannelContextInterface $channelContext,
        ChannelInterface $channel,
        GetResponseEvent $event,
        Request $request
    ): void {
        $resourceMetadata = (new ResourceMetadata())->withCollectionOperations(['get' => ['sylius_shop_api' => true]]);
        $resourceMetadataFactory->create('Foo')->willReturn($resourceMetadata);

        $channel->getCode()->willReturn('WEB_GB');
        $channelContext->getChannel()->willReturn($channel);

        $request->attributes = new ParameterBag(['channelCode' => 'WEB_US', '_api_resource_class' => 'Foo', '_api_collection_operation_name' => 'get']);
        $event->getRequest()->willReturn($request);

        $this->shouldThrow(NotFoundHttpException::class)->during('onKernelRequest', [$event]);
    }

    function it_ensures_there_is_channel_code_in_request_attributes(
        ResourceMetadataFactoryInterface $resourceMetadataFactory,
        GetResponseEvent $event,
        Request $request
    ): void {
        $resourceMetadata = (new ResourceMetadata())->withCollectionOperations(['get' => ['sylius_shop_api' => true]]);
        $resourceMetadataFactory->create('Foo')->willReturn($resourceMetadata);

        $request->attributes = new ParameterBag(['_api_resource_class' => 'Foo', '_api_collection_operation_name' => 'get']);
        $event->getRequest()->willReturn($request);

        $this->shouldThrow(\LogicException::class)->during('onKernelRequest', [$event]);
    }

    function it_does_nothing_if_not_api_operation(
        ChannelContextInterface $channelContext,
        GetResponseEvent $event,
        Request $request
    ): void {
        $request->attributes = new ParameterBag([]);
        $event->getRequest()->willReturn($request);

        $channelContext->getChannel()->shouldNotBeCalled();

        $this->onKernelRequest($event);
    }
}
