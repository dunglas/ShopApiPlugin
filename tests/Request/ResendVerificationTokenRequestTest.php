<?php

declare(strict_types=1);

namespace Tests\Sylius\ShopApiPlugin\Request;

use PHPUnit\Framework\TestCase;
use Sylius\ShopApiPlugin\Command\Customer\SendVerificationToken;
use Sylius\ShopApiPlugin\Request\Customer\ResendVerificationTokenRequest;

final class ResendVerificationTokenRequestTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_send_verification_token_command()
    {
        $resendVerificationTokenRequest = new ResendVerificationTokenRequest('daffy@the-duck.com', 'WEB_GB');

        $this->assertEquals($resendVerificationTokenRequest->getCommand(), new SendVerificationToken('daffy@the-duck.com', 'WEB_GB'));
    }
}
