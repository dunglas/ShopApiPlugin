<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request\AddressBook;

use Sylius\ShopApiPlugin\Command\AddressBook\SetDefaultAddress;
use Sylius\ShopApiPlugin\Command\CommandInterface;
use Sylius\ShopApiPlugin\Request\CommandRequestInterface;
use Symfony\Component\HttpFoundation\Request;

class SetDefaultAddressRequest implements CommandRequestInterface
{
    /** @var mixed */
    protected $id;

    /** @var string */
    protected $userEmail;

    public function __construct(Request $request, string $userEmail)
    {
        $this->id = $request->attributes->get('id');
        $this->userEmail = $userEmail;
    }

    /**
     * {@inheritdoc}
     *
     * @return SetDefaultAddress
     */
    public function getCommand(): CommandInterface
    {
        return new SetDefaultAddress($this->id, $this->userEmail);
    }
}
