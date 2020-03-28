<?php


namespace App\Test\Repository;


use App\Entity\Product;
use App\Repository\Products;
use App\Services\Database\Database;
use Mockery;
use PHPUnit\Framework\TestCase;
use App\Test\Data\EntityData;

class ProductsTest extends TestCase
{
    /**
     * @var Products
     */
    protected $productRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $database = Mockery::mock(Database::class);
        $database->shouldReceive('select')->andReturn($database);
        $database->shouldReceive('from')->andReturn($database);
        $database->shouldReceive('get')->andReturn(EntityData::getProducts());

        $this->productRepository = new Products('', Product::class, $database);
    }

    public function testGet(): void
    {
        $products = $this->productRepository->get();

        $this->assertCount(3, $products);

        $this->assertInstanceOf(Product::class, $products[0]);
    }
}
