<?php

declare(strict_types=1);

namespace spec\Sylius\ShopApiPlugin\Handler\Customer;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\User\Repository\UserRepositoryInterface;
use Sylius\ShopApiPlugin\Command\Customer\SendResetPasswordToken;
use Sylius\ShopApiPlugin\Email\PasswordResetEmail;
use Sylius\ShopApiPlugin\Handler\Customer\SendResetPasswordTokenHandler;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\SerializerStamp;

final class SendResetPasswordTokenHandlerSpec extends ObjectBehavior
{
    function let(UserRepositoryInterface $userRepository, ChannelRepositoryInterface $channelRepository, MessageBusInterface $emailBus): void
    {
        $this->beConstructedWith($userRepository, $channelRepository, $emailBus);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(SendResetPasswordTokenHandler::class);
    }

    function it_handles_dispatching_password_reset_email_message(
        UserRepositoryInterface $userRepository,
        ChannelRepositoryInterface $channelRepository,
        MessageBusInterface $emailBus,
        ShopUserInterface $user,
        ChannelInterface $channel
    ): void {
        $email = 'example@customer.com';
        $passwordResetToken = 'SOMERANDOMSTRINGASDAFSASFAFAFAACEAFCCEFACVAFVSF';
        $channelCode = 'WEB_GB';

        $userRepository->findOneByEmail($email)->willReturn($user);
        $user->getPasswordResetToken()->willReturn($passwordResetToken);

        $channelRepository->findOneByCode($channelCode)->willReturn($channel);

        $passwordResetEmail = new PasswordResetEmail($email, $passwordResetToken, $user->getWrappedObject(), $channel->getWrappedObject());
        $envelope = (new Envelope($passwordResetEmail))
            ->with(new SerializerStamp([
                'groups' => [
                    'sylius_shop_api_email',
                    'sylius_shop_api_email_password_reset',
                ],
            ]));
        $emailBus->dispatch($envelope)->willReturn($envelope)->shouldBeCalled();

        $this(new SendResetPasswordToken($email, $channelCode));
    }

    function it_throws_an_exception_if_user_has_not_been_found(
        UserRepositoryInterface $userRepository
    ): void {
        $userRepository->findOneByEmail('example@customer.com')->willReturn(null);

        $this->shouldThrow(\InvalidArgumentException::class)->during('__invoke', [new SendResetPasswordToken('example@customer.com', 'WEB_GB')]);
    }

    function it_throws_an_exception_if_user_has_not_verification_token(
        UserRepositoryInterface $userRepository,
        ShopUserInterface $user
    ): void {
        $userRepository->findOneByEmail('example@customer.com')->willReturn($user);
        $user->getPasswordResetToken()->willReturn(null);

        $this->shouldThrow(\InvalidArgumentException::class)->during('__invoke', [new SendResetPasswordToken('example@customer.com', 'WEB_GB')]);
    }
}
