<?php

declare(strict_types=1);

namespace App\Domain;

use Symfony\Component\Uid\Uuid;
abstract class AggregateRoot
{
    protected string $id;

    public function __construct()
    {
        $this->id = Uuid::v7()->toRfc4122();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->getId();
    }
}
