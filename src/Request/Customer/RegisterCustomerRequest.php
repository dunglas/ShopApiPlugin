<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request\Customer;

use Sylius\ShopApiPlugin\Command\CommandInterface;
use Sylius\ShopApiPlugin\Command\Customer\RegisterCustomer;
use Sylius\ShopApiPlugin\Request\CommandRequestInterface;

class RegisterCustomerRequest implements CommandRequestInterface
{
    protected $email;
    protected $plainPassword;
    protected $firstName;
    protected $lastName;
    protected $channelCode;

    public function __construct(string $email = '', string $plainPassword = '', string $firstName = '', string $lastName = '', string $channelCode = '')
    {
        $this->email = $email;
        $this->plainPassword = $plainPassword;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->channelCode = $channelCode;
    }

    /**
     * {@inheritdoc}
     *
     * @return RegisterCustomer
     */
    public function getCommand(): CommandInterface
    {
        return new RegisterCustomer($this->email, $this->plainPassword, $this->firstName, $this->lastName, $this->channelCode);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getChannelCode(): string
    {
        return $this->channelCode;
    }
}
