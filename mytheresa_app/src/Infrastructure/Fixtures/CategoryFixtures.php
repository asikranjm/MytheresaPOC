<?php

namespace App\Infrastructure\Fixtures;

use App\Domain\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const CATEGORY_NAMES = [
        'boots',
        'sandals',
        'sneakers',
        'shirts',
        'pants',
        'jackets',
        'dresses',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::CATEGORY_NAMES as $name) {
            $category = Category::create($name);
            $manager->persist($category);
            $this->addReference('category_'.$name, $category);
        }

        $manager->flush();
    }
}
