<?php

declare(strict_types=1);

namespace App\Domain;

class Product extends AggregateRoot
{
    private function __construct(
        private string $sku,
        private string $name,
        private int $price,
        private Category $category,
        private readonly \DateTimeImmutable $createdAt,
        private \DateTimeImmutable          $updatedAt
    )
    {
        parent::__construct();
    }

    public static function create(
        string $sku,
        string $name,
        int $price,
        Category $category,
    ): self
    {
        return new self(
            $sku,
            $name,
            $price,
            $category,
            new \DateTimeImmutable(),
            new \DateTimeImmutable(),
        );
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
