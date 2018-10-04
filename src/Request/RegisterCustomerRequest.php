<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request;

use Sylius\ShopApiPlugin\Command\RegisterCustomer;
use Symfony\Component\HttpFoundation\Request;

final class RegisterCustomerRequest
{
    /** @var string */
    public $email;

    /** @var string */
    public $plainPassword;

    /** @var string */
    public $firstName;

    /** @var string */
    public $lastName;

    /** @var string */
    public $channelCode;

    public static function fromRequest(Request $request): self
    {
        $self = new self();
        $self->email = $request->request->get('email');
        $self->plainPassword = $request->request->get('plainPassword');
        $self->firstName = $request->request->get('firstName');
        $self->lastName = $request->request->get('lastName');
        $self->channelCode = $request->request->get('channel');

        return $self;
    }

    public function getCommand(): RegisterCustomer
    {
        return new RegisterCustomer($this->email, $this->plainPassword, $this->firstName, $this->lastName, $this->channelCode);
    }
}
