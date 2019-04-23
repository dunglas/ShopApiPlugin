<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request\Customer;

use Sylius\ShopApiPlugin\Command\CommandInterface;
use Sylius\ShopApiPlugin\Command\Customer\VerifyAccount;
use Sylius\ShopApiPlugin\Request\CommandRequestInterface;

class VerifyAccountRequest implements CommandRequestInterface
{
    protected $token;

    public function __construct(string $token = '')
    {
        $this->token = $token;
    }

    /**
     * {@inheritdoc}
     *
     * @return VerifyAccount
     */
    public function getCommand(): CommandInterface
    {
        return new VerifyAccount($this->token);
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
