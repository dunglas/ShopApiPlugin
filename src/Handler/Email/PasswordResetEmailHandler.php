<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Handler\Email;

use Sylius\Component\Mailer\Sender\SenderInterface;
use Sylius\ShopApiPlugin\Email\PasswordResetEmail;
use Sylius\ShopApiPlugin\Mailer\Emails;

final class PasswordResetEmailHandler
{
    private $sender;

    public function __construct(SenderInterface $sender)
    {
        $this->sender = $sender;
    }

    public function __invoke(PasswordResetEmail $passwordResetEmail): void
    {
        $this->sender->send(
            Emails::RESET_PASSWORD_TOKEN,
            [$passwordResetEmail->getEmail()],
            [
                'user' => $passwordResetEmail->getUser(),
                'channel' => $passwordResetEmail->getChannel(),
            ]
        );
    }
}
