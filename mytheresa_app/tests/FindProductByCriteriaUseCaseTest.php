<?php

declare(strict_types=1);

namespace tests;

use App\Application\Response\ProductResponse;
use App\Application\Response\ProductResponseConverter;
use App\Application\Services\DiscountCalculatorInterface;
use App\Application\UseCases\FindProductByCriteriaUseCase;
use App\Domain\Category;
use App\Domain\Product;
use App\Domain\ProductRepositoryInterface;
use PHPUnit\Framework\TestCase;

class FindProductByCriteriaUseCaseTest extends TestCase
{
    private FindProductByCriteriaUseCase $useCase;

    protected function setUp(): void
    {
        $discountCalculator = new class implements DiscountCalculatorInterface {
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
        };

        $products = $this->generateProducts();
        $repository = new class($products) implements ProductRepositoryInterface {
            private array $all;
            public function __construct(array $all) { $this->all = $all; }
            public function findByCriteria(array $criteria): array
            {
                $out = $this->all;
                if (isset($criteria['category'])) {
                    $out = array_filter($out, fn(Product $p)=>
                        $p->getCategory()->getName() === $criteria['category']
                    );
                }
                if (isset($criteria['priceLessThan'])) {
                    $max = $criteria['priceLessThan'];
                    $out = array_filter($out, fn(Product $p)=>
                        $p->getPrice() <= $max
                    );
                }
                return array_values($out);
            }
        };

        $converter = new ProductResponseConverter();

        $this->useCase = new FindProductByCriteriaUseCase(
            $discountCalculator,
            $repository,
            $converter
        );
    }

    private function generateProducts(): array
    {
        $cats = [
            'boots'    => Category::create('boots'),
            'sandals'  => Category::create('sandals'),
            'sneakers' => Category::create('sneakers'),
        ];
        $list = [];
        for ($i = 1; $i <= 15; $i++) {
            $sku = str_pad((string)$i, 6, '0', STR_PAD_LEFT);
            $cat = $cats[array_rand($cats)];
            $price = random_int(10000, 200000);
            $list[] = Product::create($sku, "Prod $i", $price, $cat);
        }
        $list[2] = Product::create('000003', 'Special 3', 71000, $cats['boots']);
        return $list;
    }

    public function testLimitToFiveResults(): void
    {
        $responses = ($this->useCase)([]);
        $this->assertCount(5, $responses);
        $this->assertInstanceOf(ProductResponse::class, $responses[0]);
    }

    public function testFilterByCategory(): void
    {
        // Count how many boots in fixture
        $bootsCount = count(array_filter($this->generateProducts(), fn($p)=>
            $p->getCategory()->getName() === 'boots'
        ));
        $responses = ($this->useCase)(['category' => 'boots']);
        $this->assertLessThanOrEqual(5, count($responses));
        foreach ($responses as $res) {
            $this->assertEquals('boots', $res->category);
        }
    }

    public function testFilterByPriceLessThan(): void
    {
        $threshold = 50000;
        $responses = ($this->useCase)(['priceLessThan' => $threshold]);
        foreach ($responses as $res) {
            $this->assertLessThanOrEqual($threshold, $res->price->original);
        }
    }

    public function testDiscountApplication(): void
    {
        $responses = ($this->useCase)([]);
        $found = array_filter($responses, fn($r)=> $r->sku === '000003');
        if (count($found) > 0) {
            $item = array_shift($found);
            $this->assertEquals('30%', $item->price->discountPercentage);
            $this->assertEquals(
                (int)round(71000 * 0.7),
                $item->price->final
            );
        } else {
            $this->markTestSkipped('000003 is not in the first 5 results');
        }
    }
}
