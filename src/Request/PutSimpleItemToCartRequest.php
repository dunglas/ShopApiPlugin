<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request;

use Sylius\ShopApiPlugin\Command\PutSimpleItemToCart;
use Symfony\Component\HttpFoundation\Request;

final class PutSimpleItemToCartRequest
{
    /** @var string */
    public $token;

    /** @var string */
    public $productCode;

    /** @var int */
    public $quantity;

    public static function fromArray(array $item): self
    {
        $request = new self();
        $request->token = $item['token'] ?? null;
        $request->productCode = $item['productCode'] ?? null;
        $request->quantity = $item['quantity'] ?? null;

        return $request;
    }

    public static function fromRequest(Request $httpRequest): self
    {
        $request = new self();
        $request->token = $httpRequest->attributes->get('token');
        $request->productCode = $httpRequest->request->get('productCode');
        $request->quantity = $httpRequest->request->getInt('quantity', 1);

        return $request;
    }

    public function getCommand(): PutSimpleItemToCart
    {
        return new PutSimpleItemToCart($this->token, $this->productCode, $this->quantity);
    }
}
