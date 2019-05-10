<?php

declare(strict_types=1);

namespace spec\Sylius\ShopApiPlugin\Handler\Customer;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\User\Repository\UserRepositoryInterface;
use Sylius\ShopApiPlugin\Command\Customer\SendVerificationToken;
use Sylius\ShopApiPlugin\Email\VerificationEmail;
use Sylius\ShopApiPlugin\Handler\Customer\SendVerificationTokenHandler;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\SerializerStamp;

final class SendVerificationTokenHandlerSpec extends ObjectBehavior
{
    function let(UserRepositoryInterface $userRepository, ChannelRepositoryInterface $channelRepository, MessageBusInterface $emailBus): void
    {
        $this->beConstructedWith($userRepository, $channelRepository, $emailBus);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(SendVerificationTokenHandler::class);
    }

    function it_handles_dispatching_verification_email_message(
        UserRepositoryInterface $userRepository,
        ChannelRepositoryInterface $channelRepository,
        MessageBusInterface $emailBus,
        ShopUserInterface $user,
        ChannelInterface $channel
    ): void {
        $email = 'example@customer.com';
        $emailVerificationToken = 'SOMERANDOMSTRINGASDAFSASFAFAFAACEAFCCEFACVAFVSF';
        $channelCode = 'WEB_GB';

        $userRepository->findOneByEmail($email)->willReturn($user);
        $user->getEmailVerificationToken()->willReturn($emailVerificationToken);

        $channelRepository->findOneByCode($channelCode)->willReturn($channel);

        $verificationEmail = new VerificationEmail($email, $emailVerificationToken, $user->getWrappedObject(), $channel->getWrappedObject());
        $envelope = (new Envelope($verificationEmail))
            ->with(new SerializerStamp([
                'groups' => [
                    'sylius_shop_api_email',
                    'sylius_shop_api_email_verification',
                ],
            ]));
        $emailBus->dispatch($envelope)->willReturn($envelope)->shouldBeCalled();

        $this(new SendVerificationToken($email, $channelCode));
    }

    function it_throws_an_exception_if_user_has_not_been_found(
        UserRepositoryInterface $userRepository
    ): void {
        $userRepository->findOneByEmail('example@customer.com')->willReturn(null);

        $this->shouldThrow(\InvalidArgumentException::class)->during('__invoke', [new SendVerificationToken('example@customer.com', 'WEB_GB')]);
    }

    function it_throws_an_exception_if_user_has_not_verification_token(
        UserRepositoryInterface $userRepository,
        ShopUserInterface $user
    ): void {
        $userRepository->findOneByEmail('example@customer.com')->willReturn($user);
        $user->getEmailVerificationToken()->willReturn(null);

        $this->shouldThrow(\InvalidArgumentException::class)->during('__invoke', [new SendVerificationToken('example@customer.com', 'WEB_GB')]);
    }
}
