<?php

declare(strict_types=1);

namespace App\UI\Controllers;

use App\Application\Handlers\FindProductByCriteriaHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class ProductListController extends ApiController
{
    public function __construct(
        private readonly FindProductByCriteriaHandler $productHandler
    )
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $products = ($this->productHandler)($request);
        return $this->createResponse($products);
    }
}
