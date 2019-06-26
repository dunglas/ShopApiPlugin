<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request\Product;

use Sylius\ShopApiPlugin\Command\CommandInterface;
use Sylius\ShopApiPlugin\Command\Product\AddProductReviewByCode;
use Sylius\ShopApiPlugin\Request\CommandRequestInterface;

class AddProductReviewByCodeRequest implements CommandRequestInterface
{
    protected $productCode;
    protected $title;
    protected $rating;
    protected $comment;
    protected $email;
    protected $channelCode;

    public function __construct(string $productCode = '', string $title = '', int $rating = -1, string $comment = '', string $email = '', string $channelCode = '')
    {
        $this->productCode = $productCode;
        $this->title = $title;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->email = $email;
        $this->channelCode = $channelCode;
    }

    /**
     * {@inheritdoc}
     *
     * @return AddProductReviewByCode
     */
    public function getCommand(): CommandInterface
    {
        return new AddProductReviewByCode($this->productCode, $this->title, $this->rating, $this->comment, $this->email, $this->channelCode);
    }

    public function getProductCode(): string
    {
        return $this->productCode;
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
