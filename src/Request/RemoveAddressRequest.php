<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request;

use Symfony\Component\HttpFoundation\Request;

final class RemoveAddressRequest implements CommandRequestInterface
{
    /** @var int|string */
    public $id;

    public static function fromRequest(Request $request): self
    {
        $self = new self();
        $self->id = $request->attributes->get('id');

        return $self;
    }

    /** @return int|string */
    public function id()
    {
        return $this->id;
    }
}
