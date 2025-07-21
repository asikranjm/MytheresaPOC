<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\Response\ProductResponseConverter;
use App\Application\Services\DiscountCalculatorInterface;
use App\Domain\Product;
use App\Domain\ProductRepositoryInterface;

final readonly class FindProductByCriteriaUseCase
{
    public function __construct(
        private DiscountCalculatorInterface $discountCalculator,
        private ProductRepositoryInterface $productReadRepository,
        private ProductResponseConverter $responseConverter
    )
    {
    }

    public function __invoke(array $criteria): array
    {
        $products = $this->productReadRepository->findByCriteria($criteria);

        $chunk = array_slice($products, 0, 5);

        return array_map(
            fn(Product $product) => $this->responseConverter->convert(
                $product,
                $this->discountCalculator->calculate($product)
            ),
            $chunk
        );
    }
}
