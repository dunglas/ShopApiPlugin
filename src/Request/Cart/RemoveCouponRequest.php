<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request\Cart;

use Sylius\ShopApiPlugin\Command\Cart\RemoveCoupon;
use Sylius\ShopApiPlugin\Command\CommandInterface;
use Sylius\ShopApiPlugin\Request\CommandRequestInterface;
use Symfony\Component\HttpFoundation\Request;

class RemoveCouponRequest implements CommandRequestInterface
{
    /** @var string */
    protected $token;

    public function __construct(Request $request)
    {
        $this->token = $request->attributes->get('token');
    }

    /**
     * {@inheritdoc}
     *
     * @return RemoveCoupon
     */
    public function getCommand(): CommandInterface
    {
        return new RemoveCoupon($this->token);
    }
}
