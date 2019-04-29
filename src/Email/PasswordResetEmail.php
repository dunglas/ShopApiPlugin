<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Email;

use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Core\Model\ShopUserInterface;

final class PasswordResetEmail
{
    private $email;
    private $passwordResetToken;
    private $user;
    private $channel;

    public function __construct(string $email, string $passwordResetToken, ShopUserInterface $user, ChannelInterface $channel)
    {
        $this->email = $email;
        $this->passwordResetToken = $passwordResetToken;
        $this->user = $user;
        $this->channel = $channel;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPasswordResetToken(): string
    {
        return $this->passwordResetToken;
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
