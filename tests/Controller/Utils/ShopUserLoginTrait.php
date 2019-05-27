<?php

declare(strict_types=1);

namespace Tests\Sylius\ShopApiPlugin\Controller\Utils;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Response;

trait ShopUserLoginTrait
{
    /** @var Client */
    protected $client;

    protected function logInUser(string $username, string $password): void
    {
        $loginData =
<<<JSON
        {
            "email": "$username",
            "password": "$password"
        }
JSON;

        $this->client->request('POST', '/shop-api/login', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json',
        ], $loginData);

        $this->assertSame($this->client->getResponse()->getStatusCode(), Response::HTTP_OK);

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->client->setServerParameter('HTTP_AUTHORIZATION', sprintf('Bearer %s', $responseData['token']));
    }
}
