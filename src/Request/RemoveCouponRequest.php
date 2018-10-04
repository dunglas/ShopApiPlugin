<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request;

use Sylius\ShopApiPlugin\Command\CommandInterface;
use Sylius\ShopApiPlugin\Command\RemoveCoupon;
use Symfony\Component\HttpFoundation\Request;

final class RemoveCouponRequest implements CommandRequestInterface
{
    /** @var string */
    public $token;

    public static function fromRequest(Request $request): self
    {
        $self = new self();
        $self->token = $request->attributes->get('token');

        return $self;
    }

    public function getCommand(): CommandInterface
    {
        return new RemoveCoupon($this->token);
    }
}
