<?php

namespace App\Infrastructure\Fixtures;

use App\Domain\Category;
use App\Domain\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Random\RandomException;

class ProductsFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }

    /**
     * @throws RandomException
     */
    public function load(ObjectManager $manager): void
    {
        $total = 25000;
        for ($i = 1; $i <= $total; $i++) {
            $categoryName = CategoryFixtures::CATEGORY_NAMES[array_rand(CategoryFixtures::CATEGORY_NAMES)];
            /** @var Category $category */
            $category = $this->getReference('category_'.$categoryName, Category::class);

            $name = sprintf(
                '%s product %d',
                ucfirst($categoryName),
                $i
            );

            $price = random_int(10000, 100000);

            $sku = str_pad((string)$i, 6, '0', STR_PAD_LEFT);
            $product = Product::create(
                $sku,
                $name,
                (float)$price,
                $category
            );
            $manager->persist($product);

            if ($i % 1000 === 0) {
                $manager->flush();
                $manager->clear();
                foreach (CategoryFixtures::CATEGORY_NAMES as $catName) {
                    $this->setReference(
                        'category_'.$catName,
                        $manager->getRepository(Category::class)
                            ->findOneBy(['name' => $catName])
                    );
                }
            }
        }

        $manager->flush();
    }
}
