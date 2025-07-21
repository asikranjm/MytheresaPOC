<?php

declare(strict_types=1);

namespace App\Application\Response;

class Price
{
    public function __construct(
        public int $original,
        public int $final,
        public ?string $discountPercentage,
        public string $currency
    )
    {
    }
}
