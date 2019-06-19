<?php

declare(strict_types=1);

namespace Tests\Sylius\ShopApiPlugin\Request;

use PHPUnit\Framework\TestCase;
use Sylius\ShopApiPlugin\Command\Product\AddProductReviewByCode;
use Sylius\ShopApiPlugin\Request\Product\AddProductReviewByCodeRequest;

final class AddProductReviewByCodeRequestTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_review_with_author()
    {
        $addReviewRequest = new AddProductReviewByCodeRequest(
            'PALE_ALE_CODE',
            'Awesome beer',
            5,
            'I love this beer',
            'pale.ale@brewery.com',
            'WEB_GB'
        );

        $this->assertEquals($addReviewRequest->getCommand(), new AddProductReviewByCode(
            'PALE_ALE_CODE',
            'Awesome beer',
            5,
            'I love this beer',
            'pale.ale@brewery.com',
            'WEB_GB'
        ));
    }
}
