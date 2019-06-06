<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Routing;

use ApiPlatform\Core\Api\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

final class Router implements RouterInterface, UrlGeneratorInterface
{
    private $decorated;
    private $requestStack;

    public function __construct(RouterInterface $decorated, RequestStack $requestStack)
    {
        $this->decorated = $decorated;
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function setContext(RequestContext $context): void
    {
        $this->decorated->setContext($context);
    }

    /**
     * {@inheritdoc}
     */
    public function getContext(): RequestContext
    {
        return $this->decorated->getContext();
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteCollection(): RouteCollection
    {
        return $this->decorated->getRouteCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function match($pathInfo): array
    {
        return $this->decorated->match($pathInfo);
    }

    /**
     * {@inheritdoc}
     */
    public function generate($name, $parameters = [], $referenceType = self::ABS_PATH): string
    {
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            return $this->decorated->generate($name, $parameters, $referenceType);
        }

        $originalContext = $this->decorated->getContext();
        $context = clone $originalContext;

        if (!$context->hasParameter('channelCode')) {
            $context->setParameter('channelCode', $request->attributes->get('channelCode'));
        }

        $this->decorated->setContext($context);

        try {
            return $this->decorated->generate($name, $parameters, $referenceType);
        } finally {
            $this->decorated->setContext($originalContext);
        }
    }
}
