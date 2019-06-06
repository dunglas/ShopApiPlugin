<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;

final class TaxonItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private $taxonRepository;

    public function __construct(TaxonRepositoryInterface $taxonRepository)
    {
        $this->taxonRepository = $taxonRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return is_a($resourceClass, TaxonInterface::class, true);
    }

    /**
     * {@inheritdoc}
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?object
    {
        if (!\is_string($id)) {
            throw new \LogicException(sprintf('Taxon code expected, got "%s".', $id));
        }

        $code = $id;

        return $this->taxonRepository->findOneBy(['code' => $code]);
    }
}
