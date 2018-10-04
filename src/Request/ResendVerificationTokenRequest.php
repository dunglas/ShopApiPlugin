<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request;

use Sylius\ShopApiPlugin\Command\CommandInterface;
use Sylius\ShopApiPlugin\Command\SendVerificationToken;
use Symfony\Component\HttpFoundation\Request;

final class ResendVerificationTokenRequest implements CommandRequestInterface
{
    /** @var string */
    public $email;

    public static function fromRequest(Request $request): self
    {
        $self = new self();
        $self->email = $request->request->get('email');

        return $self;
    }

    public function getCommand(): CommandInterface
    {
        return new SendVerificationToken($this->email);
    }
}
