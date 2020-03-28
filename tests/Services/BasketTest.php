<?php


namespace App\Test\Services;


use App\Entity\Product;
use App\Repository\Deliveries;
use App\Repository\Offers;
use App\Repository\Products;
use App\Repository\RepositoryManager;
use App\Test\Data\EntityData;
use Mockery;
use App\Services\Basket\Basket;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class BasketTest extends TestCase
{
    /**
     * @var Basket
     */
    protected $basket;

    protected $repositoryManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repositoryManager = Mockery::mock(RepositoryManager::class);

        $productRepository = Mockery::mock(Products::class);
        $productRepository->shouldReceive('getOne')
            ->andReturn(new Product(...array_values(EntityData::getProducts()[0])));

        $offersRepository = Mockery::mock(Offers::class);
        $offersRepository->shouldReceive('get')
            ->andReturn([]);

        $offersRepository->shouldReceive('calculateOfferValue')
            ->andReturn(0);

        $deliveryRepository = Mockery::mock(Deliveries::class);
        $deliveryRepository->shouldReceive('get')
            ->andReturn([]);

        $deliveryRepository->shouldReceive('calculateDeliveryFromValue')
            ->andReturn(0);

        $this->repositoryManager
            ->shouldReceive('getRepository')
            ->withArgs(['products'])
            ->andReturn($productRepository);

        $this->repositoryManager
            ->shouldReceive('getRepository')
            ->withArgs(['offers'])
            ->andReturn($offersRepository);

        $this->repositoryManager
            ->shouldReceive('getRepository')
            ->withArgs(['deliveries'])
            ->andReturn($deliveryRepository);

        $this->basket = new Basket($this->repositoryManager);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testAddProduct(): void
    {
        $this->basket->addProduct('P1');

        $basketProducts = $this->getPrivatePropertyValue($this->basket, 'productsInBasket');

        $this->assertCount(1, $basketProducts);

        $this->basket->addProduct('P1');

        $basketProducts = $this->getPrivatePropertyValue($this->basket, 'productsInBasket');

        $this->assertCount(2, $basketProducts);
    }

    public function testGetTotal(): void
    {
        $total = $this->basket->getTotal();

        $this->assertEquals(0, $total);

        $this->basket->addProduct('P1');

        $total = $this->basket->getTotal();

        $this->assertEquals(10, $total);
    }

    public function getPrivatePropertyValue( $className, $propertyName ) {
        $reflector = new ReflectionClass( $className );
        $property = $reflector->getProperty( $propertyName );
        $property->setAccessible( true );

        return $property->getValue($className);
    }
}
