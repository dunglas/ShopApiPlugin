<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request\Cart;

use Sylius\ShopApiPlugin\Command\Cart\PutOptionBasedConfigurableItemToCart;
use Sylius\ShopApiPlugin\Command\CommandInterface;
use Sylius\ShopApiPlugin\Request\CommandRequestInterface;
use Symfony\Component\HttpFoundation\Request;

class PutOptionBasedConfigurableItemToCartRequest implements CommandRequestInterface
{
    /** @var string */
    protected $token;

    /** @var string */
    protected $productCode;

    /** @var array|null */
    protected $options;

    /** @var int */
    protected $quantity;

    protected function __construct($token, $productCode, $options, $quantity)
    {
        $this->token = $token;
        $this->productCode = $productCode;
        $this->options = $options;
        $this->quantity = $quantity;
    }

    public static function fromArray(array $item): self
    {
        return new self($item['token'] ?? null, $item['productCode'] ?? null, $item['options'] ?? null, $item['quantity'] ?? null);
    }

    public static function fromRequest(Request $request): self
    {
        return new self($request->attributes->get('token'), $request->request->get('productCode'), $request->request->get('options'), $request->request->getInt('quantity', 1));
    }

    /**
     * {@inheritdoc}
     *
     * @return PutOptionBasedConfigurableItemToCart
     */
    public function getCommand(): CommandInterface
    {
        return new PutOptionBasedConfigurableItemToCart($this->token, $this->productCode, $this->options, $this->quantity);
    }
}
