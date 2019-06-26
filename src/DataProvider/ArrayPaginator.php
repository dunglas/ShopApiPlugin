<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\DataProvider;

use ApiPlatform\Core\DataProvider\PaginatorInterface;

/**
 * Paginator for arrays.
 *
 * @see https://github.com/api-platform/core/blob/50a15afefd91a5f772a7f23ca47ed77a69012fca/src/DataProvider/ArrayPaginator.php
 *
 * @todo remove when we require API Platform >= 2.5
 */
final class ArrayPaginator implements \IteratorAggregate, PaginatorInterface
{
    private $iterator;
    private $firstResult;
    private $maxResults;
    private $totalItems;

    public function __construct(array $results, int $firstResult, int $maxResults)
    {
        if ($maxResults > 0) {
            $this->iterator = new \LimitIterator(new \ArrayIterator($results), $firstResult, $maxResults);
        } else {
            $this->iterator = new \EmptyIterator();
        }
        $this->firstResult = $firstResult;
        $this->maxResults = $maxResults;
        $this->totalItems = \count($results);
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentPage(): float
    {
        if (0 >= $this->maxResults) {
            return 1.;
        }

        return floor($this->firstResult / $this->maxResults) + 1.;
    }

    /**
     * {@inheritdoc}
     */
    public function getLastPage(): float
    {
        if (0 >= $this->maxResults) {
            return 1.;
        }

        return ceil($this->totalItems / $this->maxResults) ?: 1.;
    }

    /**
     * {@inheritdoc}
     */
    public function getItemsPerPage(): float
    {
        return (float) $this->maxResults;
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalItems(): float
    {
        return (float) $this->totalItems;
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return iterator_count($this->iterator);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): \Traversable
    {
        return $this->iterator;
    }
}
