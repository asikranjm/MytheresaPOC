<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repositories;

use App\Domain\Product;
use App\Domain\ProductRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findByCriteria(array $criteria): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('p')
            ->from(Product::class, 'p');

        if (isset($criteria['category'])) {
            $qb
                ->andWhere('p.category = :category')
                ->setParameter('category', $criteria['category']);
        }

        if (isset($criteria['priceLessThan'])) {
            $qb
                ->andWhere('p.price <= :maxPrice')
                ->setParameter('maxPrice', $criteria['priceLessThan']);
        }

        return $qb->getQuery()->getResult();
    }
}
