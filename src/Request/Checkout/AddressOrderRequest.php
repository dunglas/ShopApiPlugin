<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request\Checkout;

use Sylius\ShopApiPlugin\Command\Cart\AddressOrder;
use Sylius\ShopApiPlugin\Command\CommandInterface;
use Sylius\ShopApiPlugin\Model\Address;
use Sylius\ShopApiPlugin\Request\CommandRequestInterface;
use Symfony\Component\HttpFoundation\Request;

class AddressOrderRequest implements CommandRequestInterface
{
    /** @var string|null */
    protected $token;

    /** @var array|null */
    protected $shippingAddress;

    /** @var array|null */
    protected $billingAddress;

    public function __construct(Request $request)
    {
        $this->token = $request->attributes->get('token');
        $this->shippingAddress = $request->request->get('shippingAddress');
        $this->billingAddress = $request->request->get('billingAddress') ?: $request->request->get('shippingAddress');
    }

    /**
     * {@inheritdoc}
     *
     * @return AddressOrder
     */
    public function getCommand(): CommandInterface
    {
        return new AddressOrder(
            $this->token,
            Address::createFromArray($this->shippingAddress),
            Address::createFromArray($this->billingAddress)
        );
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function getShippingAddress(): ?array
    {
        return $this->shippingAddress;
    }

    public function getBillingAddress(): ?array
    {
        return $this->billingAddress;
    }
}
