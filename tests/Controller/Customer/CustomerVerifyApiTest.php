<?php

declare(strict_types=1);

namespace Tests\Sylius\ShopApiPlugin\Controller\Customer;

use Sylius\Component\User\Repository\UserRepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Tests\Sylius\ShopApiPlugin\Controller\JsonApiTestCase;
use Tests\Sylius\ShopApiPlugin\Controller\Utils\PurgeSpooledMessagesTrait;

final class CustomerVerifyApiTest extends JsonApiTestCase
{
    use PurgeSpooledMessagesTrait;

    /**
     * @test
     */
    public function it_allows_to_verify_customer(): void
    {
        $this->loadFixturesFromFiles(['channel.yml']);

        $registerData =
<<<EOT
        {
            "firstName": "Vin",
            "lastName": "Diesel",
            "email": "vinny@fandf.com",
            "plainPassword": "somepass"
        }
EOT;

        $this->client->request('POST', '/shop-api/WEB_GB/register', [], [], self::CONTENT_TYPE_HEADER, $registerData);

        /** @var UserRepositoryInterface $userRepository */
        $userRepository = $this->get('sylius.repository.shop_user');
        $user = $userRepository->findOneByEmail('vinny@fandf.com');

        $verifyData =
<<<EOT
        {
            "token": "{$user->getEmailVerificationToken()}"
        }
EOT;

        $this->client->request('POST', '/shop-api/WEB_GB/verify-account', [], [], self::CONTENT_TYPE_HEADER, $verifyData);

        $response = $this->client->getResponse();
        $this->assertResponseCode($response, Response::HTTP_NO_CONTENT);
    }

    /**
     * @test
     */
    public function it_does_not_allow_to_verify_customer_in_non_existent_channel(): void
    {
        $this->loadFixturesFromFiles(['channel.yml']);

        $registerData =
<<<EOT
        {
            "firstName": "Vin",
            "lastName": "Diesel",
            "email": "vinny@fandf.com",
            "plainPassword": "somepass"
        }
EOT;

        $this->client->request('POST', '/shop-api/WEB_GB/register', [], [], self::CONTENT_TYPE_HEADER, $registerData);

        /** @var UserRepositoryInterface $userRepository */
        $userRepository = $this->get('sylius.repository.shop_user');
        $user = $userRepository->findOneByEmail('vinny@fandf.com');

        $verifyData =
<<<EOT
        {
            "token": "{$user->getEmailVerificationToken()}"
        }
EOT;

        $this->client->request('POST', '/shop-api/SPACE_KLINGON/verify-account', [], [], self::CONTENT_TYPE_HEADER, $verifyData);

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'channel_has_not_been_found_response', Response::HTTP_NOT_FOUND);
    }

    protected function getContainer(): ContainerInterface
    {
        return static::$sharedKernel->getContainer();
    }
}
