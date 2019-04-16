<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Factory\Customer;

use Sylius\Component\Core\Model\CustomerInterface;

interface CustomerViewFactoryInterface
{
    public function create(CustomerInterface $customer);
}
