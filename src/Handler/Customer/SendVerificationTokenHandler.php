<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Handler\Customer;

use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\User\Repository\UserRepositoryInterface;
use Sylius\ShopApiPlugin\Command\Customer\SendVerificationToken;
use Sylius\ShopApiPlugin\Email\VerificationEmail;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\SerializerStamp;
use Webmozart\Assert\Assert;

final class SendVerificationTokenHandler
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

    public function __invoke(SendVerificationToken $resendVerificationToken): void
    {
        $email = $resendVerificationToken->email();

        /** @var ShopUserInterface $user */
        $user = $this->userRepository->findOneByEmail($email);

        Assert::notNull($user, sprintf('User with %s email has not been found.', $email));
        Assert::notNull($user->getEmailVerificationToken(), sprintf('User with %s email has not verification token defined.', $email));

        $channel = $this->channelRepository->findOneByCode($resendVerificationToken->channelCode());

        $message = new VerificationEmail($email, $user->getEmailVerificationToken(), $user, $channel);

        $this->emailBus->dispatch(
            (new Envelope($message))
                ->with(new SerializerStamp([
                    'groups' => [
                        'sylius_shop_api_email',
                        'sylius_shop_api_email_verification',
                    ],
                ]))
        );
    }
}
