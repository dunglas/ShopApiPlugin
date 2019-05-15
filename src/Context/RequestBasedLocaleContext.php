<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Context;

use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Locale\Context\LocaleNotFoundException;
use Sylius\Component\Locale\Provider\LocaleProviderInterface;
use Sylius\ShopApiPlugin\Metadata\Resource\OperationCheckerTrait;
use Symfony\Component\HttpFoundation\RequestStack;

final class RequestBasedLocaleContext implements LocaleContextInterface
{
    use OperationCheckerTrait;

    private $requestStack;
    private $localeProvider;

    public function __construct(RequestStack $requestStack, ResourceMetadataFactoryInterface $resourceMetadataFactory, LocaleProviderInterface $localeProvider)
    {
        $this->requestStack = $requestStack;
        $this->resourceMetadataFactory = $resourceMetadataFactory;
        $this->localeProvider = $localeProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocaleCode(): string
    {
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            throw new LocaleNotFoundException('Request not available.');
        }

        if (!$this->isSyliusShopApiOperation($request)) {
            throw new LocaleNotFoundException('Not handling a ShopApiPlugin operation.');
        }

        if (null === $localeCode = $request->query->get('locale')) {
            return $this->localeProvider->getDefaultLocaleCode();
        }

        $availableLocalesCodes = $this->localeProvider->getAvailableLocalesCodes();
        if (!\in_array($localeCode, $availableLocalesCodes, true)) {
            throw LocaleNotFoundException::notAvailable($localeCode, $availableLocalesCodes);
        }

        return $localeCode;
    }
}
