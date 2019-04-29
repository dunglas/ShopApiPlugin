<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Command\Customer;

use Sylius\ShopApiPlugin\Command\CommandInterface;

class GenerateVerificationToken implements CommandInterface
{
    protected $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function email(): string
    {
        return $this->email;
    }
}
