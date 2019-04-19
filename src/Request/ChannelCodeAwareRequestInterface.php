<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request;

interface ChannelCodeAwareRequestInterface
{
    public function withChannelCode(string $channelCode): self;
}
