<?php

declare(strict_types=1);

namespace Tests\Sylius\ShopApiPlugin\Controller\Customer;

use Symfony\Component\HttpFoundation\Response;
use Tests\Sylius\ShopApiPlugin\Controller\JsonApiTestCase;
use Tests\Sylius\ShopApiPlugin\Controller\Utils\ShopUserLoginTrait;

final class LoggedInCustomerDetailsApiTest extends JsonApiTestCase
{
    use ShopUserLoginTrait;

    /**
     * @test
     */
    public function it_shows_currently_logged_in_customer_details(): void
    {
        $this->loadFixturesFromFiles(['channel.yml', 'customer.yml']);
        $this->logInUser('oliver@queen.com', '123password');

        $this->client->request('GET', '/shop-api/WEB_GB/me', [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'customer/logged_in_customer_details_response', Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function it_does_not_show_customer_details_without_being_logged_in(): void
    {
        $this->loadFixturesFromFiles(['channel.yml', 'customer.yml']);

        $this->client->request('GET', '/shop-api/WEB_GB/me', [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $response = $this->client->getResponse();
        $this->assertResponseCode($response, Response::HTTP_NOT_FOUND);
    }

    /**
     * @test
     */
    public function it_does_not_show_currently_logged_in_customer_details_in_non_existent_channel(): void
    {
        $this->loadFixturesFromFiles(['channel.yml', 'customer.yml']);
        $this->logInUser('oliver@queen.com', '123password');

        $this->client->request('GET', '/shop-api/SPACE_KLINGON/me', [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'channel_has_not_been_found_response', Response::HTTP_NOT_FOUND);
    }
}
