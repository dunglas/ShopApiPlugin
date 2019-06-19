<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\Pagination;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Core\Repository\ProductReviewRepositoryInterface;
use Sylius\Component\Review\Model\ReviewInterface;
use Sylius\ShopApiPlugin\Serializer\ContextKeys;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Webmozart\Assert\Assert;

final class ReviewCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private $productReviewRepository;
    private $productRepository;
    private $pagination;
    private $arrayPaginatorClass;

    public function __construct(ProductReviewRepositoryInterface $productReviewRepository, ProductRepositoryInterface $productRepository, Pagination $pagination)
    {
        $this->productReviewRepository = $productReviewRepository;
        $this->productRepository = $productRepository;
        $this->pagination = $pagination;
        $this->arrayPaginatorClass = class_exists(\ApiPlatform\Core\DataProvider\ArrayPaginator::class) ? \ApiPlatform\Core\DataProvider\ArrayPaginator::class : ArrayPaginator::class;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return is_a($resourceClass, ReviewInterface::class, true);
    }

    /**
     * {@inheritdoc}
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        $items = null;

        switch ($operationName) {
            case 'get_by_product_code':
                $items = $this->getItemsByProductCode($context);

                break;
            case 'get_by_product_slug':
                $items = $this->getItemsByProductSlug($context);

                break;
            default:
                // hack: this is not the ideal type of exception to throw here, but it works
                // it might be better if API Platform supports returning null here?
                throw new NotFoundHttpException(sprintf('Collection operation "%s" is not implemented.', $operationName));
        }

        Assert::isArray($items);

        $offset = $this->pagination->getOffset($resourceClass, $operationName, $context);
        $limit = $this->pagination->getLimit($resourceClass, $operationName, $context);

        return new $this->arrayPaginatorClass($items, $offset, $limit);
    }

    private function getItemsByProductCode(array $context): array
    {
        Assert::keyExists($context, ContextKeys::CHANNEL);
        $channel = $context[ContextKeys::CHANNEL];
        Assert::isInstanceOf($channel, ChannelInterface::class);

        Assert::keyExists($context, 'product_code');
        $productCode = $context['product_code'];
        Assert::string($productCode);

        $product = $this->productRepository->findOneByCode($productCode);
        Assert::true($product->hasChannel($channel));

        return $this->productReviewRepository->findBy([
            'reviewSubject' => $product->getId(),
            'status' => ReviewInterface::STATUS_ACCEPTED,
        ]);
    }

    private function getItemsByProductSlug(array $context): array
    {
        Assert::keyExists($context, ContextKeys::CHANNEL);
        $channel = $context[ContextKeys::CHANNEL];
        Assert::isInstanceOf($channel, ChannelInterface::class);

        Assert::keyExists($context, ContextKeys::LOCALE_CODE);
        $localeCode = $context[ContextKeys::LOCALE_CODE];
        Assert::string($localeCode);

        Assert::keyExists($context, 'product_slug');
        $productSlug = $context['product_slug'];
        Assert::string($productSlug);

        return $this->productReviewRepository->findAcceptedByProductSlugAndChannel($productSlug, $localeCode, $channel);
    }
}
