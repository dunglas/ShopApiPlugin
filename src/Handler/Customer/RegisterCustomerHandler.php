<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Handler\Customer;

use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\User\Repository\UserRepositoryInterface;
use Sylius\ShopApiPlugin\Command\Customer\RegisterCustomer;
use Sylius\ShopApiPlugin\Event\CustomerRegistered;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Webmozart\Assert\Assert;

final class RegisterCustomerHandler
{
    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var ChannelRepositoryInterface */
    private $channelRepository;

    /** @var FactoryInterface */
    private $userFactory;

    /** @var FactoryInterface */
    private $customerFactory;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    public function __construct(
        UserRepositoryInterface $userRepository,
        ChannelRepositoryInterface $channelRepository,
        FactoryInterface $userFactory,
        FactoryInterface $customerFactory,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->userRepository = $userRepository;
        $this->channelRepository = $channelRepository;
        $this->userFactory = $userFactory;
        $this->customerFactory = $customerFactory;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __invoke(RegisterCustomer $registerCustomer): void
    {
        $this->assertEmailIsNotTaken($registerCustomer->email());
        $this->assertChannelExists($registerCustomer->channelCode());

        /** @var CustomerInterface $customer */
        $customer = $this->customerFactory->createNew();
        $customer->setFirstName($registerCustomer->firstName());
        $customer->setLastName($registerCustomer->lastName());
        $customer->setEmail($registerCustomer->email());

        /** @var ShopUserInterface $user */
        $user = $this->userFactory->createNew();
        $user->setPlainPassword($registerCustomer->plainPassword());
        $user->setUsername($registerCustomer->email());
        $user->setCustomer($customer);

        $this->userRepository->add($user);

        $this->eventDispatcher->dispatch('sylius.customer.post_api_registered', new CustomerRegistered(
            $registerCustomer->email(),
            $registerCustomer->firstName(),
            $registerCustomer->lastName(),
            $registerCustomer->channelCode()
        ));
    }

    private function assertEmailIsNotTaken(string $email): void
    {
        Assert::null($this->userRepository->findOneByEmail($email), 'User with given email already exists.');
    }

    private function assertChannelExists(string $channelCode): void
    {
        Assert::notNull($this->channelRepository->findOneByCode($channelCode), 'Channel does not exist.');
    }
}
