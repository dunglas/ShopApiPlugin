<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Controller\Customer;

use Sylius\ShopApiPlugin\Command\Customer\GenerateResetPasswordToken;
use Sylius\ShopApiPlugin\Command\Customer\SendResetPasswordToken;
use Sylius\ShopApiPlugin\Request\Customer\RequestPasswordResettingRequest;
use Symfony\Component\Messenger\MessageBusInterface;

final class RequestPasswordResettingAction
{
    private $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(RequestPasswordResettingRequest $data): RequestPasswordResettingRequest
    {
        $this->commandBus->dispatch(new GenerateResetPasswordToken($data->getEmail()));
        $this->commandBus->dispatch(new SendResetPasswordToken($data->getEmail(), $data->getChannelCode()));

        return $data;
    }
}
