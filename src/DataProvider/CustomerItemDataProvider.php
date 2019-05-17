<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class CustomerItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return is_a($resourceClass, CustomerInterface::class, true);
    }

    /**
     * {@inheritdoc}
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?object
    {
        if ('me' !== $id) {
            return null;
        }

        if (null === $token = $this->tokenStorage->getToken()) {
            return null;
        }

        $user = $token->getUser();
        if (!$user instanceof ShopUserInterface) {
            return null;
        }

        return $user->getCustomer();
    }
}
