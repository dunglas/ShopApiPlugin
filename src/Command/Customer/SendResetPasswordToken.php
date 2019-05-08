<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Command\Customer;

use Sylius\ShopApiPlugin\Command\CommandInterface;

class SendResetPasswordToken implements CommandInterface
{
    protected $email;
    protected $channelCode;

    public function __construct(string $email, string $channelCode)
    {
        $this->email = $email;
        $this->channelCode = $channelCode;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function channelCode(): string
    {
        return $this->channelCode;
    }
}
