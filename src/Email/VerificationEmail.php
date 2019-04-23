<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Email;

use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Core\Model\ShopUserInterface;

final class VerificationEmail
{
    private $email;
    private $emailVerificationToken;
    private $user;
    private $channel;

    public function __construct(string $email, string $emailVerificationToken, ShopUserInterface $user, ChannelInterface $channel)
    {
        $this->email = $email;
        $this->emailVerificationToken = $emailVerificationToken;
        $this->user = $user;
        $this->channel = $channel;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getEmailVerificationToken(): string
    {
        return $this->emailVerificationToken;
    }

    public function getUser(): ShopUserInterface
    {
        return $this->user;
    }

    public function getChannel(): ChannelInterface
    {
        return $this->channel;
    }
}
