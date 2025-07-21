<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Domain\Category;
use App\Infrastructure\Doctrine\Repositories\CategoryRepository;

final readonly class FindCategoryByName
{

    public function __construct(
        private CategoryRepository $categoryRepository
    )
    {
    }

    public function findByName(string $name): ?Category
    {
        return $this->categoryRepository->findOneBy(['name' => $name]);
    }
}
