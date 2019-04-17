<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request\Product;

use Sylius\ShopApiPlugin\Command\CommandInterface;
use Sylius\ShopApiPlugin\Command\Product\AddProductReviewByCode;
use Sylius\ShopApiPlugin\Request\CommandRequestInterface;
use Symfony\Component\HttpFoundation\Request;

class AddProductReviewByCodeRequest implements CommandRequestInterface
{
    /** @var string */
    protected $code;

    /** @var string */
    protected $channelCode;

    /** @var string */
    protected $title;

    /** @var int */
    protected $rating;

    /** @var string */
    protected $comment;

    /** @var string */
    protected $email;

    public function __construct(Request $request)
    {
        $this->code = $request->attributes->get('code');
        $this->channelCode = $request->attributes->get('channelCode');

        $this->title = $request->request->get('title');
        $this->rating = $request->request->get('rating');
        $this->comment = $request->request->get('comment');
        $this->email = $request->request->get('email');
    }

    /**
     * {@inheritdoc}
     *
     * @return AddProductReviewByCode
     */
    public function getCommand(): CommandInterface
    {
        return new AddProductReviewByCode($this->code, $this->channelCode, $this->title, $this->rating, $this->comment, $this->email);
    }
}
