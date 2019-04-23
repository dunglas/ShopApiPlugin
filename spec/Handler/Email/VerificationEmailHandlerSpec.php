<?php

declare(strict_types=1);

namespace spec\Sylius\ShopApiPlugin\Handler\Email;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Sylius\ShopApiPlugin\Email\VerificationEmail;
use Sylius\ShopApiPlugin\Handler\Email\VerificationEmailHandler;
use Sylius\ShopApiPlugin\Mailer\Emails;

final class VerificationEmailHandlerSpec extends ObjectBehavior
{
    function let(SenderInterface $sender): void
    {
        $this->beConstructedWith($sender);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(VerificationEmailHandler::class);
    }

    function it_handles_emailing_user_with_verification_email(
        SenderInterface $sender,
        ShopUserInterface $user,
        ChannelInterface $channel
    ): void {
        $email = 'example@customer.com';
        $emailVerificationToken = 'SOMERANDOMSTRINGASDAFSASFAFAFAACEAFCCEFACVAFVSF';

        $sender->send(Emails::EMAIL_VERIFICATION_TOKEN, [$email], [
            'user' => $user,
            'channel' => $channel,
        ])->shouldBeCalled();

        $this(new VerificationEmail($email, $emailVerificationToken, $user->getWrappedObject(), $channel->getWrappedObject()));
    }
}
