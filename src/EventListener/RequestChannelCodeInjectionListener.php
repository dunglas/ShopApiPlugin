<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\EventListener;

use Sylius\ShopApiPlugin\Request\ChannelCodeAwareRequestInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

final class RequestChannelCodeInjectionListener
{
    public function onKernelRequest(GetResponseEvent $event): void
    {
        $request = $event->getRequest();
        $data = $request->attributes->get('data');

        if (!$data instanceof ChannelCodeAwareRequestInterface) {
            return;
        }

        $channelCode = $request->attributes->get('channelCode', '');
        $data = $data->withChannelCode($channelCode);

        $request->attributes->set('data', $data);
    }
}
