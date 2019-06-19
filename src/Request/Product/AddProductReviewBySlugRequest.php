<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request\Product;

use Sylius\ShopApiPlugin\Command\CommandInterface;
use Sylius\ShopApiPlugin\Command\Product\AddProductReviewBySlug;
use Sylius\ShopApiPlugin\Request\CommandRequestInterface;

class AddProductReviewBySlugRequest implements CommandRequestInterface
{
    protected $productSlug;
    protected $title;
    protected $rating;
    protected $comment;
    protected $email;
    protected $channelCode;

    public function __construct(string $productSlug = '', string $title = '', int $rating = -1, string $comment = '', string $email = '', string $channelCode = '')
    {
        $this->productSlug = $productSlug;
        $this->title = $title;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->email = $email;
        $this->channelCode = $channelCode;
    }

    /**
     * {@inheritdoc}
     *
     * @return AddProductReviewBySlug
     */
    public function getCommand(): CommandInterface
    {
        return new AddProductReviewBySlug($this->productSlug, $this->title, $this->rating, $this->comment, $this->email, $this->channelCode);
    }

    public function getProductSlug(): string
    {
        return $this->productSlug;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getChannelCode(): string
    {
        return $this->channelCode;
    }
}
