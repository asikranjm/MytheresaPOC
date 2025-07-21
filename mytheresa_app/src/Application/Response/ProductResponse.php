<?php

declare(strict_types=1);

namespace App\Application\Response;

class ProductResponse
{
    public function __construct(
        public string $sku,
        public string $name,
        public string $category,
        public Price $price
    )
    {
    }
}
