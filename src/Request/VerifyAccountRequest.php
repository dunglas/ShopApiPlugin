<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request;

use Sylius\ShopApiPlugin\Command\CommandInterface;
use Sylius\ShopApiPlugin\Command\VerifyAccount;
use Symfony\Component\HttpFoundation\Request;

final class VerifyAccountRequest implements CommandRequestInterface
{
    /** @var string */
    public $token;

    public static function fromRequest(Request $request): self
    {
        $self = new self();
        $self->token = $request->request->get('token');

        return $self;
    }

    public function getCommand(): CommandInterface
    {
        return new VerifyAccount($this->token);
    }
}
