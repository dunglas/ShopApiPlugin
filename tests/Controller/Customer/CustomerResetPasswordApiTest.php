<?php

declare(strict_types=1);

namespace Tests\Sylius\ShopApiPlugin\Controller\Customer;

use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\User\Repository\UserRepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Tests\Sylius\ShopApiPlugin\Controller\JsonApiTestCase;
use Tests\Sylius\ShopApiPlugin\Controller\Utils\PurgeSpooledMessagesTrait;

final class CustomerResetPasswordApiTest extends JsonApiTestCase
{
    use PurgeSpooledMessagesTrait;

    /**
     * @test
     */
    public function it_allows_to_reset_customer_password(): void
    {
        $this->loadFixturesFromFiles(['channel.yml', 'customer.yml']);

        $requestResetData =
<<<JSON
            {
                "email": "oliver@queen.com"
            }
JSON;

        $this->client->request('POST', '/shop-api/WEB_GB/request-password-reset', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'ACCEPT' => 'application/json',
        ], $requestResetData);

        /** @var UserRepositoryInterface $userRepository */
        $userRepository = $this->get('sylius.repository.shop_user');

        /** @var ShopUserInterface $user */
        $user = $userRepository->findOneByEmail('oliver@queen.com');

        $resetPasswordData =
<<<JSON
            {
                "plainPassword": "somepass"
            }
JSON;

        $this->client->request('POST', '/shop-api/WEB_GB/password-reset/' . $user->getPasswordResetToken(), [], [], [
            'CONTENT_TYPE' => 'application/json',
            'ACCEPT' => 'application/json',
        ], $resetPasswordData);

        $response = $this->client->getResponse();
        $this->assertResponseCode($response, Response::HTTP_NO_CONTENT);
    }

    /**
     * @test
     */
    public function it_does_not_allow_to_reset_customer_password_in_non_existent_channel(): void
    {
        $this->loadFixturesFromFiles(['channel.yml', 'customer.yml']);

        $requestResetData =
<<<JSON
            {
                "email": "oliver@queen.com"
            }
JSON;

        $this->client->request('POST', '/shop-api/WEB_GB/request-password-reset', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'ACCEPT' => 'application/json',
        ], $requestResetData);

        /** @var UserRepositoryInterface $userRepository */
        $userRepository = $this->get('sylius.repository.shop_user');
        /** @var ShopUserInterface $user */
        $user = $userRepository->findOneByEmail('oliver@queen.com');

        $resetPasswordData =
<<<JSON
            {
                "plainPassword": "somepass"
            }
JSON;

        $this->client->request('POST', '/shop-api/SPACE_KLINGON/password-reset/' . $user->getPasswordResetToken(), [], [], [
            'CONTENT_TYPE' => 'application/json',
            'ACCEPT' => 'application/json',
        ], $resetPasswordData);

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'channel_has_not_been_found_response', Response::HTTP_NOT_FOUND);
    }

    protected function getContainer(): ContainerInterface
    {
        return static::$sharedKernel->getContainer();
    }
}
