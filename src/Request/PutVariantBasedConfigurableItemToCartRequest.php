<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request;

use Sylius\ShopApiPlugin\Command\PutVariantBasedConfigurableItemToCart;
use Symfony\Component\HttpFoundation\Request;

final class PutVariantBasedConfigurableItemToCartRequest
{
    /** @var string */
    public $token;

    /** @var string */
    public $productCode;

    /** @var string */
    public $variantCode;

    /** @var int */
    public $quantity;

    public static function fromArray(array $item): self
    {
        $request = new self();
        $request->token = $item['token'] ?? null;
        $request->productCode = $item['productCode'] ?? null;
        $request->variantCode = $item['variantCode'] ?? null;
        $request->quantity = $item['quantity'] ?? null;

        return $request;
    }

    public static function fromRequest(Request $httpRequest): self
    {
        $request = new self();
        $request->token = $httpRequest->attributes->get('token');
        $request->productCode = $httpRequest->request->get('productCode');
        $request->variantCode = $httpRequest->request->get('variantCode');
        $request->quantity = $httpRequest->request->getInt('quantity', 1);

        return $request;
    }

    public function getCommand(): PutVariantBasedConfigurableItemToCart
    {
        return new PutVariantBasedConfigurableItemToCart($this->token, $this->productCode, $this->variantCode, $this->quantity);
    }
}
