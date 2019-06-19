<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Command\Product;

use Sylius\ShopApiPlugin\Command\CommandInterface;

class AddProductReviewByCode implements CommandInterface
{
    protected $productCode;
    protected $title;
    protected $rating;
    protected $comment;
    protected $email;
    protected $channelCode;

    public function __construct(string $productCode, string $title, int $rating, string $comment, string $email, string $channelCode)
    {
        $this->productCode = $productCode;
        $this->title = $title;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->email = $email;
        $this->channelCode = $channelCode;
    }

    public function productCode(): string
    {
        return $this->productCode;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function rating(): int
    {
        return $this->rating;
    }

    public function comment(): string
    {
        return $this->comment;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function channelCode(): string
    {
        return $this->channelCode;
    }
}
