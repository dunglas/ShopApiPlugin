<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Command\Customer;

use Sylius\ShopApiPlugin\Command\CommandInterface;

class ResetPassword implements CommandInterface
{
    protected $token;
    protected $plainPassword;

    public function __construct(string $token, string $plainPassword)
    {
        $this->token = $token;
        $this->plainPassword = $plainPassword;
    }

    public function token(): string
    {
        return $this->token;
    }

    public function plainPassword(): string
    {
        return $this->plainPassword;
    }
}
