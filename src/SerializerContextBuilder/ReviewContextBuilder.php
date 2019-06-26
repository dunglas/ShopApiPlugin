<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\SerializerContextBuilder;

use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use Sylius\Component\Review\Model\ReviewInterface;
use Symfony\Component\HttpFoundation\Request;

final class ReviewContextBuilder implements SerializerContextBuilderInterface
{
    private $decorated;

    public function __construct(SerializerContextBuilderInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    /**
     * {@inheritdoc}
     */
    public function createFromRequest(Request $request, bool $normalization, ?array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);

        if (!$normalization || !is_a($resourceClass = $context['resource_class'] ?? null, ReviewInterface::class, true)) {
            return $context;
        }

        switch ($context['collection_operation_name'] ?? null) {
            case 'get_by_product_code':
                $context['product_code'] = $request->attributes->get('productCode');

                break;
            case 'get_by_product_slug':
                $context['product_slug'] = $request->attributes->get('productSlug');

                break;
            default:
                break;
        }

        return $context;
    }
}
