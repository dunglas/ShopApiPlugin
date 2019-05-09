<?php

declare(strict_types=1);

namespace Tests\Sylius\ShopApiPlugin\Controller\Customer;

use Sylius\Component\Core\Test\Services\EmailCheckerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Tests\Sylius\ShopApiPlugin\Controller\JsonApiTestCase;
use Tests\Sylius\ShopApiPlugin\Controller\Utils\PurgeSpooledMessagesTrait;

final class CustomerResendVerificationTokenApiTest extends JsonApiTestCase
{
    use PurgeSpooledMessagesTrait;

    /**
     * @test
     */
    public function it_allows_to_resend_verification_token(): void
    {
        $this->loadFixturesFromFiles(['channel.yml']);

        $registerData =
<<<JSON
        {
            "firstName": "Vin",
            "lastName": "Diesel",
            "email": "vinny@fandf.com",
            "plainPassword": "somepass"
        }
JSON;

        $this->client->request('POST', '/shop-api/WEB_GB/register', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'ACCEPT' => 'application/json',
        ], $registerData);

        $resendData =
<<<JSON
        {
            "email": "vinny@fandf.com"
        }
JSON;

        $this->client->request('POST', '/shop-api/WEB_GB/resend-verification-link', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'ACCEPT' => 'application/json',
        ], $resendData);

        $response = $this->client->getResponse();
        $this->assertResponseCode($response, Response::HTTP_NO_CONTENT);

        /** @var EmailCheckerInterface $emailChecker */
        $emailChecker = $this->get('sylius.behat.email_checker');

        $this->assertSame(2, $emailChecker->countMessagesTo('vinny@fandf.com'));
    }

    /**
     * @test
     */
    public function it_does_not_allow_to_resend_verification_email_if_email_is_not_defined(): void
    {
        $this->loadFixturesFromFiles(['channel.yml']);

        $resendData =
<<<JSON
        {
        }
JSON;

        $this->client->request('POST', '/shop-api/WEB_GB/resend-verification-link', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'ACCEPT' => 'application/json',
        ], $resendData);

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'customer/validation_email_not_defined_response', Response::HTTP_BAD_REQUEST);
    }

    /**
     * @test
     */
    public function it_does_not_allow_to_resend_verification_email_if_email_is_malformed(): void
    {
        $this->loadFixturesFromFiles(['channel.yml']);

        $resendData =
<<<JSON
        {
            "email": "vinnyfandf.com"
        }
JSON;

        $this->client->request('POST', '/shop-api/WEB_GB/resend-verification-link', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'ACCEPT' => 'application/json',
        ], $resendData);

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'customer/validation_email_not_valid_response', Response::HTTP_BAD_REQUEST);
    }

    /**
     * @test
     */
    public function it_does_not_allow_to_resend_verification_email_if_customer_does_not_exists(): void
    {
        $this->loadFixturesFromFiles(['channel.yml']);

        $resendData =
<<<JSON
        {
            "email": "vinny@fandf.com"
        }
JSON;

        $this->client->request('POST', '/shop-api/WEB_GB/resend-verification-link', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'ACCEPT' => 'application/json',
        ], $resendData);

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'customer/validation_email_not_found_response', Response::HTTP_BAD_REQUEST);
    }

    /**
     * @test
     */
    public function it_does_not_allow_to_resend_verification_token_in_non_existent_channel(): void
    {
        $this->loadFixturesFromFiles(['channel.yml']);

        $resendData =
<<<JSON
        {
            "email": "vinny@fandf.com"
        }
JSON;

        $this->client->request('POST', '/shop-api/SPACE_KLINGON/resend-verification-link', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'ACCEPT' => 'application/json',
        ], $resendData);

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'channel_has_not_been_found_response', Response::HTTP_NOT_FOUND);
    }

    protected function getContainer(): ContainerInterface
    {
        return static::$sharedKernel->getContainer();
    }
}
