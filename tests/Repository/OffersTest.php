<?php


namespace App\Test\Repository;


use App\Entity\Offer;
use App\Entity\Product;
use App\Repository\Offers;
use App\Services\Database\Database;
use Mockery;
use PHPUnit\Framework\TestCase;
use App\Test\Data\EntityData;

class OffersTest extends TestCase
{
    /**
     * @var Offers
     */
    protected $offersRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $database = Mockery::mock(Database::class);
        $database->shouldReceive('select')->andReturn($database);
        $database->shouldReceive('from')->andReturn($database);
        $database->shouldReceive('get')->andReturn(EntityData::getOffers());

        $this->offersRepository = new Offers('', Offer::class, $database);
    }

    public function testCalculateOfferValue(): void
    {
        $productArray = EntityData::getProducts();

        $products = [
            new Product(...array_values($productArray[0]))
        ];

        $value = $this->offersRepository->calculateOfferValue($products);

        $this->assertEquals(5, round($value, 1));

        $products = [
            new Product(...array_values($productArray[0])),
            new Product(...array_values($productArray[2]))
        ];

        $value = $this->offersRepository->calculateOfferValue($products);
        
        $this->assertEquals(12.5, round($value, 1));
    }
}
