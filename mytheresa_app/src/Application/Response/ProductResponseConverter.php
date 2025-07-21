<?php

declare(strict_types=1);

namespace App\Application\Response;

use App\Domain\Product;

class ProductResponseConverter
{
    public function convert(Product $product, ?float $discount): ProductResponse
    {
        $original = $product->getPrice();
        $final    = (int) round($original * (1 - $discount));
        $discountPercentage = $discount > 0
            ? sprintf('%d%%', (int) round($discount * 100))
            : null;

        $price = new Price(
            original:           $original,
            final:              $final,
            discountPercentage: $discountPercentage,
            currency:           'EUR'
        );

        return new ProductResponse(
            sku:           $product->getSku(),
            name:          $product->getName(),
            category:      $product->getCategory()->getName(),
            price: $price
        );
    }
}
