<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request\Customer;

use Sylius\ShopApiPlugin\Command\CommandInterface;
use Sylius\ShopApiPlugin\Command\Customer\ResetPassword;
use Sylius\ShopApiPlugin\Request\CommandRequestInterface;

class ResetPasswordRequest implements CommandRequestInterface
{
    protected $token;
    protected $plainPassword;

    public function __construct(string $token = '', string $plainPassword = '')
    {
        $this->token = $token;
        $this->plainPassword = $plainPassword;
    }

    /**
     * {@inheritdoc}
     *
     * @return ResetPassword
     */
    public function getCommand(): CommandInterface
    {
        return new ResetPassword($this->token, $this->plainPassword);
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }
}
