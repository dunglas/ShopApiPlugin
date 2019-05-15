<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\Pagination;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\ShopApiPlugin\Serializer\ContextKeys;
use Webmozart\Assert\Assert;

final class ProductCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private $productRepository;
    private $pagination;

    public function __construct(ProductRepositoryInterface $productRepository, Pagination $pagination)
    {
        $this->productRepository = $productRepository;
        $this->pagination = $pagination;
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
        Assert::keyExists($context, ContextKeys::CHANNEL);
        Assert::keyExists($context, ContextKeys::LOCALE_CODE);

        $channel = $context[ContextKeys::CHANNEL];
        $localeCode = $context[ContextKeys::LOCALE_CODE];

        Assert::isInstanceOf($channel, ChannelInterface::class);
        Assert::string($localeCode);

        $count = $this->pagination->getLimit($resourceClass, $operationName, $context);

        switch ($operationName) {
            case 'get_latest':
                $products = $this->productRepository->findLatestByChannel($channel, $localeCode, $count);

                break;
            default:
                // TODO: product list
                throw new \LogicException('Not implemented.');
        }

        return $products;
    }
}
