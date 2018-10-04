<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request;

use Sylius\ShopApiPlugin\Command\RemoveItemFromCart;
use Symfony\Component\HttpFoundation\Request;

final class RemoveItemFromCartRequest
{
    /** @var string */
    public $token;

    /** @var mixed */
    public $id;

    public static function fromRequest(Request $request): self
    {
        $self = new self();
        $self->token = $request->attributes->get('token');
        $self->id = $request->attributes->get('id');

        return $self;
    }

    public function getCommand(): RemoveItemFromCart
    {
        return new RemoveItemFromCart($this->token, $this->id);
    }
}
