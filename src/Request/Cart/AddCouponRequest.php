<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request\Cart;

use Sylius\ShopApiPlugin\Command\Cart\AddCoupon;
use Sylius\ShopApiPlugin\Command\CommandInterface;
use Sylius\ShopApiPlugin\Request\CommandRequestInterface;
use Symfony\Component\HttpFoundation\Request;

class AddCouponRequest implements CommandRequestInterface
{
    /** @var string|null */
    protected $token;

    /** @var string|null */
    protected $coupon;

    public function __construct(Request $request)
    {
        $this->token = $request->attributes->get('token');
        $this->coupon = $request->request->get('coupon');
    }

    /**
     * {@inheritdoc}
     *
     * @return AddCoupon
     */
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
