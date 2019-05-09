<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\ApiPlatform\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Sylius\ShopApiPlugin\Request\CommandRequestInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class CommandRequestDataPersister implements DataPersisterInterface
{
    private $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data): bool
    {
        return $data instanceof CommandRequestInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function persist($data)
    {
        $this->handleCommand($data);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($data)
    {
        $this->handleCommand($data);
    }

    private function handleCommand(CommandRequestInterface $data)
    {
        $this->commandBus->dispatch($data->getCommand());
    }
}
