<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request\AddressBook;

use Sylius\ShopApiPlugin\Command\AddressBook\RemoveAddress;
use Sylius\ShopApiPlugin\Command\CommandInterface;
use Sylius\ShopApiPlugin\Request\CommandRequestInterface;
use Symfony\Component\HttpFoundation\Request;

class RemoveAddressRequest implements CommandRequestInterface
{
    /** @var int|string */
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
     * @return RemoveAddress
     */
    public function getCommand(): CommandInterface
    {
        return new RemoveAddress($this->id, $this->userEmail);
    }
}
