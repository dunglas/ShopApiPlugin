<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\DataProvider;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryResultCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGenerator;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\Pagination;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use Doctrine\ORM\QueryBuilder;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Sylius\ShopApiPlugin\Serializer\ContextKeys;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Webmozart\Assert\Assert;

final class ProductCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private $productRepository;
    private $taxonRepository;
    private $pagination;
    private $collectionExtensions;

    /**
     * @param iterable|QueryCollectionExtensionInterface[] $collectionExtensions
     */
    public function __construct(ProductRepositoryInterface $productRepository, TaxonRepositoryInterface $taxonRepository, Pagination $pagination, iterable $collectionExtensions = [])
    {
        $this->productRepository = $productRepository;
        $this->taxonRepository = $taxonRepository;
        $this->pagination = $pagination;
        $this->collectionExtensions = $collectionExtensions;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return is_a($resourceClass, ProductInterface::class, true);
    }

    /**
     * {@inheritdoc}
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        $queryBuilder = null;

        switch ($operationName) {
            case 'get_latest':
                return $this->getLatestProducts($resourceClass, $operationName, $context);
            case 'get_by_taxon_code':
                $queryBuilder = $this->getQueryBuilderByTaxonCode($context);

                break;
            case 'get_by_taxon_slug':
                $queryBuilder = $this->getQueryBuilderByTaxonSlug($context);

                break;
            default:
                // hack: this is not the ideal type of exception to throw here, but it works
                // it might be better if API Platform supports returning null here?
                throw new NotFoundHttpException(sprintf('Collection operation "%s" is not implemented.', $operationName));
        }

        Assert::isInstanceOf($queryBuilder, QueryBuilder::class);

        $queryNameGenerator = new QueryNameGenerator();
        foreach ($this->collectionExtensions as $extension) {
            $extension->applyToCollection($queryBuilder, $queryNameGenerator, $resourceClass, $operationName, $context);

            if ($extension instanceof QueryResultCollectionExtensionInterface && $extension->supportsResult($resourceClass, $operationName, $context)) {
                return $extension->getResult($queryBuilder, $resourceClass, $operationName, $context);
            }
        }

        return $queryBuilder->getQuery()->getResult();
    }

    private function getLatestProducts(string $resourceClass, string $operationName, array $context): array
    {
        Assert::keyExists($context, ContextKeys::CHANNEL);
        Assert::keyExists($context, ContextKeys::LOCALE_CODE);

        $channel = $context[ContextKeys::CHANNEL];
        $localeCode = $context[ContextKeys::LOCALE_CODE];

        Assert::isInstanceOf($channel, ChannelInterface::class);
        Assert::string($localeCode);

        $count = $this->pagination->getLimit($resourceClass, $operationName, $context);

        return $this->productRepository->findLatestByChannel($channel, $localeCode, $count);
    }

    private function getQueryBuilderByTaxonCode(array $context): QueryBuilder
    {
        Assert::keyExists($context, ContextKeys::CHANNEL);
        Assert::keyExists($context, ContextKeys::LOCALE_CODE);

        $channel = $context[ContextKeys::CHANNEL];
        $localeCode = $context[ContextKeys::LOCALE_CODE];

        Assert::isInstanceOf($channel, ChannelInterface::class);
        Assert::string($localeCode);

        Assert::keyExists($context, 'taxon_code');
        $taxonCode = $context['taxon_code'];
        Assert::string($taxonCode);

        /** @var TaxonInterface $taxon */
        $taxon = $this->taxonRepository->findOneBy(['code' => $taxonCode]);

        Assert::notNull($taxon, sprintf('Taxon with code %s has not been found', $taxonCode));

        $queryBuilder = $this->productRepository->createShopListQueryBuilder($channel, $taxon, $localeCode);
        $queryBuilder->addSelect('productTaxon');
        $queryBuilder->addOrderBy('productTaxon.position');

        return $queryBuilder;
    }

    private function getQueryBuilderByTaxonSlug(array $context): QueryBuilder
    {
        Assert::keyExists($context, ContextKeys::CHANNEL);
        Assert::keyExists($context, ContextKeys::LOCALE_CODE);

        $channel = $context[ContextKeys::CHANNEL];
        $localeCode = $context[ContextKeys::LOCALE_CODE];

        Assert::isInstanceOf($channel, ChannelInterface::class);
        Assert::string($localeCode);

        Assert::keyExists($context, 'taxon_slug');
        $taxonSlug = $context['taxon_slug'];
        Assert::string($taxonSlug);

        /** @var TaxonInterface $taxon */
        $taxon = $this->taxonRepository->findOneBySlug($taxonSlug, $localeCode);

        Assert::notNull($taxon, sprintf('Taxon with slug %s in locale %s has not been found', $taxonSlug, $localeCode));

        $queryBuilder = $this->productRepository->createShopListQueryBuilder($channel, $taxon, $localeCode);
        $queryBuilder->addSelect('productTaxon');
        $queryBuilder->addOrderBy('productTaxon.position');

        return $queryBuilder;
    }
}
