<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\ApiPlatform;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Sylius\ShopApiPlugin\Request\CommandRequestInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class DataPersister implements DataPersisterInterface
{
    private $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
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
        $this->bus->dispatch($data->getCommand());
    }
}
