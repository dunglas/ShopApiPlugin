<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request\Cart;

use Sylius\ShopApiPlugin\Command\Cart\DropCart;
use Sylius\ShopApiPlugin\Command\CommandInterface;
use Sylius\ShopApiPlugin\Request\CommandRequestInterface;
use Symfony\Component\HttpFoundation\Request;

class DropCartRequest implements CommandRequestInterface
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
     * @return DropCart
     */
    public function getCommand(): CommandInterface
    {
        return new DropCart($this->token);
    }
}
