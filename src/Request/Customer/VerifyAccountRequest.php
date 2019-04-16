<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request\Customer;

use Sylius\ShopApiPlugin\Command\CommandInterface;
use Sylius\ShopApiPlugin\Command\Customer\VerifyAccount;
use Sylius\ShopApiPlugin\Request\CommandRequestInterface;
use Symfony\Component\HttpFoundation\Request;

class VerifyAccountRequest implements CommandRequestInterface
{
    /** @var string */
    protected $token;

    public function __construct(Request $request)
    {
        $this->token = $request->query->get('token');
    }

    /**
     * {@inheritdoc}
     *
     * @return VerifyAccount
     */
    public function getCommand(): CommandInterface
    {
        return new VerifyAccount($this->token);
    }
}
