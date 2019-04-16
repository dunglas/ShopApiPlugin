<?php

declare(strict_types=1);

namespace spec\Sylius\ShopApiPlugin\Http;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\ShopApiPlugin\Checker\ChannelExistenceCheckerInterface;
use Sylius\ShopApiPlugin\Exception\ChannelNotFoundException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class RequestChannelEnsurerSpec extends ObjectBehavior
{
    function let(ChannelExistenceCheckerInterface $channelExistenceChecker): void
    {
        $this->beConstructedWith($channelExistenceChecker);
    }

    function it_implements_event_subscriber_interface(): void
    {
        $this->shouldImplement(EventSubscriberInterface::class);
    }

    function it_ensures_that_channel_code_passed_in_request_is_valid(
        ChannelExistenceCheckerInterface $channelExistenceChecker,
        FilterControllerEvent $event,
        Request $request
    ): void {
        $event->getRequest()->willReturn($request);
        $request->attributes = new ParameterBag(['channelCode' => 'WEB_US']);

        $channelExistenceChecker->withCode('WEB_US')->willThrow(ChannelNotFoundException::class);

        $this
            ->shouldThrow(NotFoundHttpException::class)
            ->during('checkChannelCode', [$event])
        ;
    }

    function it_does_nothing_if_there_is_no_channel_code_in_request_attributes(
        ChannelExistenceCheckerInterface $channelExistenceChecker,
        FilterControllerEvent $event,
        Request $request
    ): void {
        $event->getRequest()->willReturn($request);
        $request->attributes = new ParameterBag([]);

        $channelExistenceChecker->withCode(Argument::any())->shouldNotBeCalled();

        $this->checkChannelCode($event);
    }
}
