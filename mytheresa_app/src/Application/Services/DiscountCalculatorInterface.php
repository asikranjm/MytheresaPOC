<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Product;

interface DiscountCalculatorInterface
{
    /**
     * @return float porcentaje en [0,1]
     */
    public function calculate(Product $product): float;
}
