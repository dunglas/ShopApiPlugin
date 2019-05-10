<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Serializer;

final class ContextKeys
{
    public const CHANNEL = 'sylius_shop_api_channel';
    public const FALLBACK_LOCALE_CODE = 'sylius_shop_api_fallback_locale_code';
    public const LOCALE_CODE = 'sylius_shop_api_locale_code';

    private function __construct()
    {
    }
}
