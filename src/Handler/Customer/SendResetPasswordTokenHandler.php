<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Handler\Customer;

use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\User\Repository\UserRepositoryInterface;
use Sylius\ShopApiPlugin\Command\Customer\SendResetPasswordToken;
use Sylius\ShopApiPlugin\Email\PasswordResetEmail;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\SerializerStamp;
use Webmozart\Assert\Assert;

final class SendResetPasswordTokenHandler
{
    private $userRepository;
    private $channelRepository;
    private $emailBus;

    public function __construct(UserRepositoryInterface $userRepository, ChannelRepositoryInterface $channelRepository, MessageBusInterface $emailBus)
    {
        $this->userRepository = $userRepository;
        $this->channelRepository = $channelRepository;
        $this->emailBus = $emailBus;
    }

    public function __invoke(SendResetPasswordToken $sendResetPasswordToken): void
    {
        $email = $sendResetPasswordToken->email();

        /** @var ShopUserInterface $user */
        $user = $this->userRepository->findOneByEmail($email);

        Assert::notNull($user, sprintf('User with %s email has not been found.', $email));
        Assert::notNull($user->getPasswordResetToken(), sprintf('User with %s email has not verification token defined.', $email));

        $channel = $this->channelRepository->findOneByCode($sendResetPasswordToken->channelCode());

        $message = new PasswordResetEmail($email, $user->getPasswordResetToken(), $user, $channel);

        $this->emailBus->dispatch(
            (new Envelope($message))
                ->with(new SerializerStamp([
                    'groups' => [
                        'sylius_shop_api_email',
                        'sylius_shop_api_email_password_reset',
                    ],
                ]))
        );
    }
}
