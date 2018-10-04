<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request;

use Symfony\Component\HttpFoundation\Request;

final class SetDefaultAddressRequest
{
    /** @var mixed */
    public $id;

    public static function fromRequest(Request $request): self
    {
        $self = new self();
        $self->id = $request->attributes->get('id');

        return $self;
    }
}
