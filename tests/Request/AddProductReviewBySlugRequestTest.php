<?php

declare(strict_types=1);

namespace Tests\Sylius\ShopApiPlugin\Request;

use PHPUnit\Framework\TestCase;
use Sylius\ShopApiPlugin\Command\Product\AddProductReviewBySlug;
use Sylius\ShopApiPlugin\Request\Product\AddProductReviewBySlugRequest;

final class AddProductReviewBySlugRequestTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_review_with_author()
    {
        $addReviewRequest = new AddProductReviewBySlugRequest(
            'pale-ale',
            'Awesome beer',
            5,
            'I love this beer',
            'pale.ale@brewery.com',
            'WEB_GB'
        );

        $this->assertEquals($addReviewRequest->getCommand(), new AddProductReviewBySlug(
            'pale-ale',
            'Awesome beer',
            5,
            'I love this beer',
            'pale.ale@brewery.com',
            'WEB_GB'
        ));
    }
}
