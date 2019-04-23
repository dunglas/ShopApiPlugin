<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Handler\Email;

use Sylius\Component\Mailer\Sender\SenderInterface;
use Sylius\ShopApiPlugin\Email\VerificationEmail;
use Sylius\ShopApiPlugin\Mailer\Emails;

final class VerificationEmailHandler
{
    private $sender;

    public function __construct(SenderInterface $sender)
    {
        $this->sender = $sender;
    }

    public function __invoke(VerificationEmail $verificationEmail): void
    {
        $this->sender->send(
            Emails::EMAIL_VERIFICATION_TOKEN,
            [$verificationEmail->getEmail()],
            [
                'user' => $verificationEmail->getUser(),
                'channel' => $verificationEmail->getChannel(),
            ]
        );
    }
}
