<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Serializer\Callback\Product;

use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductAttributeValueInterface;
use Sylius\ShopApiPlugin\Serializer\ContextKeys;
use Webmozart\Assert\Assert;

final class ProductAttributesCallback
{
    private $allowedAttributesCodes;

    /**
     * @param string[] $allowedAttributesCodes
     */
    public function __construct(array $allowedAttributesCodes)
    {
        $this->allowedAttributesCodes = $allowedAttributesCodes;
    }

    public function __invoke($attributeValue, $object, $attribute, $format, $context): iterable
    {
        if (!$object instanceof ProductInterface) {
            return $attributeValue;
        }

        $product = $object;

        Assert::keyExists($context, ContextKeys::LOCALE_CODE);
        Assert::keyExists($context, ContextKeys::FALLBACK_LOCALE_CODE);

        $localeCode = $context[ContextKeys::LOCALE_CODE];
        $fallbackLocaleCode = $context[ContextKeys::FALLBACK_LOCALE_CODE];

        Assert::string($localeCode);
        Assert::string($fallbackLocaleCode);

        $attributeValues = $product->getAttributesByLocale($localeCode, $fallbackLocaleCode);

        $attributeValues = $attributeValues->filter(function (ProductAttributeValueInterface $attributeValue): bool {
            return \in_array($attributeValue->getCode(), $this->allowedAttributesCodes, true);
        })->getValues();

        return $attributeValues;
    }
}
