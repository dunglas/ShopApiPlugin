<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request\Customer;

class RequestPasswordResettingRequest
{
    protected $email;
    protected $channelCode;

    public function __construct(string $email = '', string $channelCode = '')
    {
        $this->email = $email;
        $this->channelCode = $channelCode;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getChannelCode(): string
    {
        return $this->channelCode;
    }
}
