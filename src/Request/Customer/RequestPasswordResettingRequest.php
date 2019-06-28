<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request\Customer;

use Sylius\ShopApiPlugin\Command\CommandInterface;
use Sylius\ShopApiPlugin\Command\Customer\RequestPasswordResetting;
use Sylius\ShopApiPlugin\Request\CommandRequestInterface;

class RequestPasswordResettingRequest implements CommandRequestInterface
{
    protected $email;
    protected $channelCode;

    public function __construct(string $email = '', string $channelCode = '')
    {
        $this->email = $email;
        $this->channelCode = $channelCode;
    }

    /**
     * {@inheritdoc}
     *
     * @return RequestPasswordResetting
     */
    public function getCommand(): CommandInterface
    {
        return new RequestPasswordResetting($this->email, $this->channelCode);
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
