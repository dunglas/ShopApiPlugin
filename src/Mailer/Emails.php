<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Mailer;

final class Emails
{
    public const EMAIL_VERIFICATION_TOKEN = 'shop_api_verification_token';
    public const RESET_PASSWORD_TOKEN = 'shop_api_reset_password_token';

    private function __construct()
    {
    }
}
