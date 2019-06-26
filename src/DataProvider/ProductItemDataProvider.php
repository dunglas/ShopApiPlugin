<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\Exception\ItemNotFoundException;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\ShopApiPlugin\Serializer\ContextKeys;
use Webmozart\Assert\Assert;

final class ProductItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
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
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?object
    {
        switch ($operationName) {
            case 'get_by_code':
                return $this->getProductByCode($id, $context);
            case 'get_by_slug':
                return $this->getProductBySlug($id, $context);
            default:
                throw new ItemNotFoundException(sprintf('Item operation "%s" is not implemented.', $operationName));
        }
    }

    private function getProductByCode(string $productCode, array $context): ?ProductInterface
    {
        Assert::keyExists($context, ContextKeys::CHANNEL);
        $channel = $context[ContextKeys::CHANNEL];
        Assert::isInstanceOf($channel, ChannelInterface::class);

        $product = $this->productRepository->findOneByCode($productCode);

        if (!$product->hasChannel($channel)) {
            throw new ItemNotFoundException(sprintf('Product with code %s has not been found for channel %s.', $productCode, $channel->getCode()));
        }

        return $product;
    }

    private function getProductBySlug(string $productSlug, array $context): ?ProductInterface
    {
        Assert::keyExists($context, ContextKeys::CHANNEL);
        $channel = $context[ContextKeys::CHANNEL];
        Assert::isInstanceOf($channel, ChannelInterface::class);

        Assert::keyExists($context, ContextKeys::LOCALE_CODE);
        $localeCode = $context[ContextKeys::LOCALE_CODE];
        Assert::string($localeCode);

        return $this->productRepository->findOneByChannelAndSlug($channel, $localeCode, $productSlug);
    }
}
