<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
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
                return $this->getProductByCode($id);
            case 'get_by_slug':
                return $this->getProductBySlug($id, $context);
            default:
                return null;
        }
    }

    private function getProductByCode(string $code): ?ProductInterface
    {
        return $this->productRepository->findOneByCode($code);
    }

    private function getProductBySlug(string $slug, array $context): ?ProductInterface
    {
        Assert::keyExists($context, ContextKeys::CHANNEL);
        Assert::keyExists($context, ContextKeys::LOCALE_CODE);

        $channel = $context[ContextKeys::CHANNEL];
        $localeCode = $context[ContextKeys::LOCALE_CODE];

        Assert::isInstanceOf($channel, ChannelInterface::class);
        Assert::string($localeCode);

        return $this->productRepository->findOneByChannelAndSlug($channel, $localeCode, $slug);
    }
}
