<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request;

use Sylius\ShopApiPlugin\Command\DropCart;
use Symfony\Component\HttpFoundation\Request;

final class DropCartRequest
{
    /** @var string */
    public $token;

    public static function fromRequest(Request $request): self
    {
        $self = new self();
        $self->token = $request->attributes->get('token');

        return $self;
    }

    public function getCommand(): DropCart
    {
        return new DropCart($this->token);
    }
}
