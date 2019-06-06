<?php

declare(strict_types=1);

namespace Tests\Sylius\ShopApiPlugin\Controller\Product;

use Symfony\Component\HttpFoundation\Response;
use Tests\Sylius\ShopApiPlugin\Controller\JsonApiTestCase;

final class ProductShowDetailsByCodeApiTest extends JsonApiTestCase
{
    /**
     * @test
     */
    public function it_shows_simple_product_details_page(): void
    {
        $this->loadFixturesFromFiles(['shop.yml']);

        $this->client->request('GET', '/shop-api/WEB_GB/products/by-code/LOGAN_MUG_CODE', [], [], [
            'HTTP_ACCEPT' => 'application/ld+json',
        ]);
        $response = $this->client->getResponse();

        $this->assertResponse($response, 'product/simple_product_details_page', Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function it_does_not_show_product_details_if_product_with_specified_code_has_not_been_found(): void
    {
        $this->loadFixturesFromFiles(['shop.yml']);

        $this->client->request('GET', '/shop-api/WEB_GB/products/by-code/WRONG_PRODUCT_CODE', [], [], [
            'HTTP_ACCEPT' => 'application/ld+json',
        ]);
        $response = $this->client->getResponse();

        $this->assertResponse($response, 'item_not_found_hydra_response', Response::HTTP_NOT_FOUND);
    }

    /**
     * @test
     */
    public function it_shows_simple_product_details_page_in_different_locale(): void
    {
        $this->loadFixturesFromFiles(['shop.yml']);

        $this->client->request('GET', '/shop-api/WEB_GB/products/by-code/LOGAN_MUG_CODE?locale=de_DE', [], [], [
            'HTTP_ACCEPT' => 'application/ld+json',
        ]);
        $response = $this->client->getResponse();

        $this->assertResponse($response, 'product/german_simple_product_details_page', Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function it_shows_product_with_variant_details_page(): void
    {
        $this->loadFixturesFromFiles(['shop.yml']);

        $this->client->request('GET', '/shop-api/WEB_GB/products/by-code/LOGAN_T_SHIRT_CODE', [], [], [
            'HTTP_ACCEPT' => 'application/ld+json',
        ]);
        $response = $this->client->getResponse();

        $this->assertResponse($response, 'product/product_with_variant_details_page', Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function it_shows_product_with_options_details_page(): void
    {
        $this->loadFixturesFromFiles(['shop.yml']);

        $this->client->request('GET', '/shop-api/WEB_GB/products/by-code/LOGAN_HAT_CODE', [], [], [
            'HTTP_ACCEPT' => 'application/ld+json',
        ]);
        $response = $this->client->getResponse();

        $this->assertResponse($response, 'product/product_with_options_details_page', Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function it_shows_product_with_options_details_page_in_different_locale(): void
    {
        $this->loadFixturesFromFiles(['shop.yml']);

        $this->client->request('GET', '/shop-api/WEB_GB/products/by-code/LOGAN_HAT_CODE?locale=de_DE', [], [], [
            'HTTP_ACCEPT' => 'application/ld+json',
        ]);
        $response = $this->client->getResponse();

        $this->assertResponse($response, 'product/german_product_with_options_details_page', Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function it_does_not_show_product_details_by_code_in_non_existent_channel(): void
    {
        $this->loadFixturesFromFiles(['shop.yml']);

        $this->client->request('GET', '/shop-api/SPACE_KLINGON/products/by-code/LOGAN_MUG_CODE', [], [], [
            'HTTP_ACCEPT' => 'application/ld+json',
        ]);
        $response = $this->client->getResponse();

        $this->assertResponse($response, 'channel_has_not_been_found_hydra_response', Response::HTTP_NOT_FOUND);
    }
}
