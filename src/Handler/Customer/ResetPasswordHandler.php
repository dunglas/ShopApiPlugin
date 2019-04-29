<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Handler\Customer;

use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\User\Repository\UserRepositoryInterface;
use Sylius\ShopApiPlugin\Command\Customer\ResetPassword;
use Webmozart\Assert\Assert;

final class ResetPasswordHandler
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(ResetPassword $resetPassword): void
    {
        /** @var ShopUserInterface $user */
        $user = $this->userRepository->findOneBy(['passwordResetToken' => $resetPassword->token()]);

        Assert::notNull($user, sprintf('User has not been found.'));

        $user->setPlainPassword($resetPassword->plainPassword());
        $user->setPasswordResetToken(null);
        $user->setPasswordRequestedAt(null);
    }
}
