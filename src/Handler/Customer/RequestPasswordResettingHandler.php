<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Handler\Customer;

use Sylius\ShopApiPlugin\Command\Customer\GenerateResetPasswordToken;
use Sylius\ShopApiPlugin\Command\Customer\RequestPasswordResetting;
use Sylius\ShopApiPlugin\Command\Customer\SendResetPasswordToken;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;

final class RequestPasswordResettingHandler
{
    private $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(RequestPasswordResetting $requestPasswordResetting): void
    {
        $this->commandBus->dispatch(new GenerateResetPasswordToken($requestPasswordResetting->email()));

        $sendResetPasswordToken = new SendResetPasswordToken($requestPasswordResetting->email(), $requestPasswordResetting->channelCode());
        $this->commandBus->dispatch(
            (new Envelope($sendResetPasswordToken))
                ->with(new DispatchAfterCurrentBusStamp())
        );
    }
}
