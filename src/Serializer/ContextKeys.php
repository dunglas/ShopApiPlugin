<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Serializer;

final class ContextKeys
{
    public const PREFIX = 'sylius_shop_api_';

    public const CHANNEL = self::PREFIX . 'channel';
    public const FALLBACK_LOCALE_CODE = self::PREFIX . 'fallback_locale_code';
    public const LOCALE_CODE = self::PREFIX . 'locale_code';

    private function __construct()
    {
    }
}
