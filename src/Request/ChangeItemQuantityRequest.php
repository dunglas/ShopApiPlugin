<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request;

use Sylius\ShopApiPlugin\Command\ChangeItemQuantity;
use Symfony\Component\HttpFoundation\Request;

final class ChangeItemQuantityRequest
{
    /** @var string */
    public $token;

    /** @var mixed */
    public $id;

    /** @var int */
    public $quantity;

    public static function fromRequest(Request $request): self
    {
        $self = new self();
        $self->token = $request->attributes->get('token');
        $self->id = $request->attributes->get('id');
        $self->quantity = $request->request->getInt('quantity');

        return $self;
    }

    public function getCommand(): ChangeItemQuantity
    {
        return new ChangeItemQuantity($this->token, $this->id, $this->quantity);
    }
}
