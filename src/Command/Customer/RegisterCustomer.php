<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Command\Customer;

use Sylius\ShopApiPlugin\Command\CommandInterface;

class RegisterCustomer implements CommandInterface
{
    protected $email;
    protected $plainPassword;
    protected $firstName;
    protected $lastName;
    protected $channelCode;

    public function __construct(string $email, string $plainPassword, string $firstName, string $lastName, string $channelCode)
    {
        $this->email = $email;
        $this->plainPassword = $plainPassword;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->channelCode = $channelCode;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function plainPassword(): string
    {
        return $this->plainPassword;
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function channelCode(): string
    {
        return $this->channelCode;
    }
}
