<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Request;

use Sylius\ShopApiPlugin\Command\AddProductReviewBySlug;
use Symfony\Component\HttpFoundation\Request;

final class AddProductReviewBySlugRequest
{
    /** @var string */
    public $slug;

    /** @var string */
    public $channelCode;

    /** @var string */
    public $title;

    /** @var int */
    public $rating;

    /** @var string */
    public $comment;

    /** @var string */
    public $email;

    public static function fromRequest(Request $request): self
    {
        $self = new self();
        $self->slug = $request->attributes->get('slug');

        $self->title = $request->request->get('title');
        $self->channelCode = $request->request->get('channelCode');
        $self->rating = $request->request->get('rating');
        $self->comment = $request->request->get('comment');
        $self->email = $request->request->get('email');

        return $self;
    }

    public function getCommand(): AddProductReviewBySlug
    {
        return new AddProductReviewBySlug($this->slug, $this->channelCode, $this->title, $this->rating, $this->comment, $this->email);
    }
}
