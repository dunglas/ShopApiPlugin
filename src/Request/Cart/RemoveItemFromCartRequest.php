<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request\Cart;

use Sylius\ShopApiPlugin\Command\Cart\RemoveItemFromCart;
use Sylius\ShopApiPlugin\Command\CommandInterface;
use Sylius\ShopApiPlugin\Request\CommandRequestInterface;
use Symfony\Component\HttpFoundation\Request;

class RemoveItemFromCartRequest implements CommandRequestInterface
{
    /** @var string */
    protected $token;

    /** @var mixed */
    protected $id;

    public function __construct(Request $request)
    {
        $this->token = $request->attributes->get('token');
        $this->id = $request->attributes->get('id');
    }

    /**
     * {@inheritdoc}
     *
     * @return RemoveItemFromCart
     */
    public function getCommand(): CommandInterface
    {
        return new RemoveItemFromCart($this->token, $this->id);
    }
}
