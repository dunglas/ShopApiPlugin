<?php

declare(strict_types=1);

namespace Tests\Sylius\ShopApiPlugin\Controller\Product;

use Symfony\Component\HttpFoundation\Response;
use Tests\Sylius\ShopApiPlugin\Controller\JsonApiTestCase;

final class ProductAddReviewBySlugApiTest extends JsonApiTestCase
{
    /**
     * @test
     */
    public function it_adds_review_to_product(): void
    {
        $this->loadFixturesFromFiles(['shop.yml']);

        $createData =
<<<JSON
        {
            "title": "Awesome product",
            "rating": 5,
            "comment": "If I were a mug, I would like to be like this one!",
            "email": "oliver@example.com"
        }
JSON;
        $this->client->request('POST', '/shop-api/WEB_GB/products/by-slug/logan-mug/reviews', [], [], [
            'CONTENT_TYPE' => 'application/ld+json',
            'HTTP_ACCEPT' => 'application/ld+json',
        ], $createData);
        $response = $this->client->getResponse();

        $this->assertResponseCode($response, Response::HTTP_CREATED);
    }

    /**
     * @test
     */
    public function it_adds_review_to_the_product_for_registered_user(): void
    {
        $this->loadFixturesFromFiles(['shop.yml', 'customer.yml']);

        $createData =
<<<JSON
        {
            "title": "Awesome product",
            "rating": 5,
            "comment": "If I were a mug, I would like to be like this one!",
            "email": "oliver@example.com"
        }
JSON;
        $this->client->request('POST', '/shop-api/WEB_GB/products/by-slug/logan-mug/reviews', [], [], [
            'CONTENT_TYPE' => 'application/ld+json',
            'HTTP_ACCEPT' => 'application/ld+json',
        ], $createData);
        $response = $this->client->getResponse();

        $this->assertResponseCode($response, Response::HTTP_CREATED);
    }

    /**
     * @test
     */
    public function it_does_not_allow_to_add_review_when_rating_is_out_of_bounds(): void
    {
        $this->loadFixturesFromFiles(['channel.yml', 'shop.yml']);

        $createData =
<<<JSON
        {
            "comment": "Hello",
            "rating": 100,
            "email": "test@test.com",
            "title": "Testing"
        }
JSON;

        $this->client->request('POST', '/shop-api/WEB_GB/products/by-slug/logan-mug/reviews', [], [], [
            'CONTENT_TYPE' => 'application/ld+json',
            'HTTP_ACCEPT' => 'application/ld+json',
        ], $createData);

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'reviews/add_review_failed_rating', Response::HTTP_BAD_REQUEST);
    }

    /**
     * @test
     */
    public function it_does_not_allow_to_add_review_when_rating_email_is_not_valid(): void
    {
        $this->loadFixturesFromFiles(['channel.yml', 'shop.yml']);

        $createData =
<<<JSON
        {
            "comment": "Hello",
            "rating": 4,
            "email": "test.com",
            "title": "Testing"
        }
JSON;

        $this->client->request('POST', '/shop-api/WEB_GB/products/by-slug/logan-mug/reviews', [], [], [
            'CONTENT_TYPE' => 'application/ld+json',
            'HTTP_ACCEPT' => 'application/ld+json',
        ], $createData);

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'reviews/add_review_failed_email', Response::HTTP_BAD_REQUEST);
    }

    /**
     * @test
     */
    public function it_does_not_allow_to_add_product_review_by_slug_in_non_existent_channel(): void
    {
        $this->loadFixturesFromFiles(['shop.yml']);

        $createData =
<<<JSON
        {
            "title": "Awesome product",
            "rating": 5,
            "comment": "If I were a mug, I would like to be like this one!",
            "email": "oliver@example.com"
        }
JSON;
        $this->client->request('POST', '/shop-api/SPACE_KLINGON/products/by-slug/logan-mug/reviews', [], [], [
            'CONTENT_TYPE' => 'application/ld+json',
            'HTTP_ACCEPT' => 'application/ld+json',
        ], $createData);
        $response = $this->client->getResponse();

        $this->assertResponse($response, 'channel_has_not_been_found_hydra_response', Response::HTTP_NOT_FOUND);
    }
}
