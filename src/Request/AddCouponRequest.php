<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request;

use Sylius\ShopApiPlugin\Command\AddCoupon;
use Sylius\ShopApiPlugin\Command\CommandInterface;
use Symfony\Component\HttpFoundation\Request;

final class AddCouponRequest implements CommandRequestInterface
{
    /** @var string|null */
    public $token;

    /** @var string|null */
    public $coupon;

    public static function fromRequest(Request $request): self
    {
        $self = new self();
        $self->token = $request->attributes->get('token');
        $self->coupon = $request->request->get('coupon');

        return $self;
    }

    public function getCommand(): CommandInterface
    {
        return new AddCoupon($this->token, $this->coupon);
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function getCoupon(): ?string
    {
        return $this->coupon;
    }
}
