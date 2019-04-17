<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request\Customer;

use Sylius\ShopApiPlugin\Command\CommandInterface;
use Sylius\ShopApiPlugin\Command\Customer\SendVerificationToken;
use Sylius\ShopApiPlugin\Request\CommandRequestInterface;
use Symfony\Component\HttpFoundation\Request;

class ResendVerificationTokenRequest implements CommandRequestInterface
{
    /** @var string */
    protected $email;

    /** @var string */
    protected $channelCode;

    public function __construct(Request $request)
    {
        $this->email = $request->request->get('email');
        $this->channelCode = $request->attributes->get('channelCode');
    }

    /**
     * {@inheritdoc}
     *
     * @return SendVerificationToken
     */
    public function getCommand(): CommandInterface
    {
        return new SendVerificationToken($this->email, $this->channelCode);
    }
}
