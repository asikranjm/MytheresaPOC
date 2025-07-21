<?php

declare(strict_types=1);

namespace App\Domain;

class Category extends AggregateRoot
{
    private function __construct(
        private readonly string $name
    )
    {
        parent::__construct();
    }

    public static function create(string $name): self
    {
        return new self($name);
    }

    public function getName(): string
    {
        return $this->name;
    }
}
