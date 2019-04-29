<?php

declare(strict_types=1);

namespace spec\Sylius\ShopApiPlugin\Handler\Email;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Sylius\ShopApiPlugin\Email\PasswordResetEmail;
use Sylius\ShopApiPlugin\Handler\Email\PasswordResetEmailHandler;
use Sylius\ShopApiPlugin\Mailer\Emails;

final class PasswordResetEmailHandlerSpec extends ObjectBehavior
{
    function let(SenderInterface $sender): void
    {
        $this->beConstructedWith($sender);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(PasswordResetEmailHandler::class);
    }

    function it_handles_emailing_user_with_verification_email(
        SenderInterface $sender,
        ShopUserInterface $user,
        ChannelInterface $channel
    ): void {
        $email = 'example@customer.com';
        $passwordResetToken = 'SOMERANDOMSTRINGASDAFSASFAFAFAACEAFCCEFACVAFVSF';

        $sender->send(Emails::RESET_PASSWORD_TOKEN, [$email], [
            'user' => $user,
            'channel' => $channel,
        ])->shouldBeCalled();

        $this(new PasswordResetEmail($email, $passwordResetToken, $user->getWrappedObject(), $channel->getWrappedObject()));
    }
}
