<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\SerializerContextBuilder;

use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;
use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use Sylius\Bundle\ResourceBundle\Controller\ParametersParserInterface;
use Sylius\ShopApiPlugin\Metadata\Resource\AttributeKeys;
use Symfony\Component\HttpFoundation\Request;

final class ParametersContextBuilder implements SerializerContextBuilderInterface
{
    private $decorated;
    private $resourceMetadataFactory;
    private $parametersParser;

    public function __construct(SerializerContextBuilderInterface $decorated, ResourceMetadataFactoryInterface $resourceMetadataFactory, ParametersParserInterface $parametersParser)
    {
        $this->decorated = $decorated;
        $this->resourceMetadataFactory = $resourceMetadataFactory;
        $this->parametersParser = $parametersParser;
    }

    /**
     * {@inheritdoc}
     */
    public function createFromRequest(Request $request, bool $normalization, ?array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);

        if ($normalization || null === $resourceClass = $context['resource_class'] ?? null) {
            return $context;
        }

        $resourceMetadata = $this->resourceMetadataFactory->create($resourceClass);

        $attribute = $resourceMetadata->getOperationAttribute($extractedAttributes, AttributeKeys::SYLIUS_SHOP_API, null, true);

        if (!isset($attribute['parameters'])) {
            return $context;
        }

        $context['default_constructor_arguments'][$resourceClass] = $this->parametersParser->parseRequestValues($attribute['parameters'], $request);

        return $context;
    }
}
