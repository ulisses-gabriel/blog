<?php

declare(strict_types=1);

namespace App\Database;

class Paginator
{
    private array $items;
    private int $pages;
    private int $currentPage;
    private int $itemsCount;
    private int $perPage;

    public function __construct(array $items = [])
    {
        $this->items = $items;
        $this->itemsCount = count($items);
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): Paginator
    {
        $this->items = $items;

        return $this;
    }

    public function getPages(): int
    {
        return $this->pages;
    }

    public function setPages(int $pages): Paginator
    {
        $this->pages = $pages;

        return $this;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function setCurrentPage(int $currentPage): Paginator
    {
        $this->currentPage = $currentPage;

        return $this;
    }

    public function getItemsCount(): int
    {
        return $this->itemsCount;
    }

    public function setItemsCount(int $itemsCount): Paginator
    {
        $this->itemsCount = $itemsCount;

        return $this;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function setPerPage(int $perPage): Paginator
    {
        $this->perPage = $perPage;

        return $this;
    }

    public function hasMorePages(): bool
    {
        return $this->currentPage < $this->pages;
    }

    public function nextPage(): int
    {
        if ($this->currentPage === $this->pages) {
            return $this->currentPage;
        }

        return $this->currentPage+1;
    }

    public function previousPage(): int
    {
        if ($this->currentPage === 1) {
            return $this->currentPage;
        }

        return $this->currentPage-1;
    }

    public function hasPages(): bool
    {
        return $this->pages > 0;
    }
}