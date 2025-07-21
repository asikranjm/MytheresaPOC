<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Product;

final readonly class DiscountCalculator implements DiscountCalculatorInterface
{

    public function calculate(Product $product): float
    {
        $rules = [];

        if ($product->getCategory()->getName() === 'boots') {
            $rules[] = 0.30;
        }

        if ($product->getSku() === '000003') {
            $rules[] = 0.15;
        }

        return empty($rules) ? 0.0 : max($rules);
    }
}
