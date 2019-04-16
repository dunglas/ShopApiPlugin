<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request\Customer;

use Sylius\ShopApiPlugin\Command\CommandInterface;
use Sylius\ShopApiPlugin\Command\Customer\RegisterCustomer;
use Sylius\ShopApiPlugin\Request\CommandRequestInterface;
use Symfony\Component\HttpFoundation\Request;

class RegisterCustomerRequest implements CommandRequestInterface
{
    /** @var string */
    protected $email;

    /** @var string */
    protected $plainPassword;

    /** @var string */
    protected $firstName;

    /** @var string */
    protected $lastName;

    /** @var string */
    protected $channelCode;

    public function __construct(Request $request)
    {
        $this->channelCode = $request->attributes->get('channelCode');

        $this->email = $request->request->get('email');
        $this->plainPassword = $request->request->get('plainPassword');
        $this->firstName = $request->request->get('firstName');
        $this->lastName = $request->request->get('lastName');
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
}
