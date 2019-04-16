<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request\Cart;

use Sylius\ShopApiPlugin\Command\Cart\ChangeItemQuantity;
use Sylius\ShopApiPlugin\Command\CommandInterface;
use Sylius\ShopApiPlugin\Request\CommandRequestInterface;
use Symfony\Component\HttpFoundation\Request;

class ChangeItemQuantityRequest implements CommandRequestInterface
{
    /** @var string */
    protected $token;

    /** @var mixed */
    protected $id;

    /** @var int */
    protected $quantity;

    public function __construct(Request $request)
    {
        $this->token = $request->attributes->get('token');
        $this->id = $request->attributes->get('id');
        $this->quantity = $request->request->getInt('quantity');
    }

    /**
     * {@inheritdoc}
     *
     * @return ChangeItemQuantity
     */
    public function getCommand(): CommandInterface
    {
        return new ChangeItemQuantity($this->token, $this->id, $this->quantity);
    }
}
