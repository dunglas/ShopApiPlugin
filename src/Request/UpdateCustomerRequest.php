<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request;

use DateTimeInterface;
use Sylius\ShopApiPlugin\Command\UpdateCustomer;
use Symfony\Component\HttpFoundation\Request;

final class UpdateCustomerRequest
{
    /** @var string */
    public $firstName;

    /** @var string */
    public $lastName;

    /** @var string|null */
    public $email;

    /** @var DateTimeInterface|null */
    public $birthday;

    /** @var string */
    public $gender;

    /** @var string|null */
    public $phoneNumber;

    /** @var bool */
    public $subscribedToNewsletter;

    public static function fromRequest(Request $request): self
    {
        $self = new self();
        $self->firstName = $request->request->get('firstName');
        $self->lastName = $request->request->get('lastName');
        $self->email = $request->request->get('email');
        $self->birthday = $request->request->get('birthday');
        $self->gender = $request->request->get('gender');
        $self->phoneNumber = $request->request->get('phoneNumber');
        $self->subscribedToNewsletter = $request->request->getBoolean('subscribedToNewsletter') ?? false;

        return $self;
    }

    public function getCommand(): UpdateCustomer
    {
        return new UpdateCustomer($this->firstName, $this->lastName, $this->email, $this->birthday, $this->gender, $this->phoneNumber, $this->subscribedToNewsletter);
    }
}
