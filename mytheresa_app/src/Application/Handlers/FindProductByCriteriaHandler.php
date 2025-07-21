<?php

declare(strict_types=1);

namespace App\Application\Handlers;


use App\Application\UseCases\FindCategoryByName;
use App\Application\UseCases\FindProductByCriteriaUseCase;
use Symfony\Component\HttpFoundation\Request;

final readonly class FindProductByCriteriaHandler
{
    public function __construct(
        private FindProductByCriteriaUseCase $productUseCase,
        private FindCategoryByName $findCategoryByName
    )
    {
    }

    public function __invoke(Request $request): array
    {
        $criteria = [];
        if ($request->query->has('category')) {
            $category = $this->findCategoryByName->findByName($request->get('category'));
            $criteria['category'] = $category?->getId();
        }

        if ($request->query->has('priceLessThan')) {
            $criteria['priceLessThan'] = (int) $request->get('priceLessThan');
        }

        return ($this->productUseCase)($criteria);
    }
}
