<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request;

use Sylius\ShopApiPlugin\Command\CommandInterface;
use Sylius\ShopApiPlugin\Command\PickupCart;
use Symfony\Component\HttpFoundation\Request;

final class PickupCartRequest implements CommandRequestInterface
{
    /** @var string */
    public $token;

    /** @var string */
    public $channel;

    public static function fromRequest(Request $request): self
    {
        $self = new self();
        $self->token = $request->attributes->get('token');
        $self->channel = $request->request->get('channel');

        return $self;
    }

    public function getCommand(): CommandInterface
    {
        return new PickupCart($this->token, $this->channel);
    }
}
