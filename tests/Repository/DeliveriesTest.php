<?php


namespace App\Test\Repository;


use App\Entity\Delivery;
use App\Repository\Deliveries;
use App\Services\Database\Database;
use App\Test\Data\EntityData;
use Mockery;
use PHPUnit\Framework\TestCase;

class DeliveriesTest extends TestCase
{
    /**
     * @var Deliveries
     */
    protected $deliveryRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $database = Mockery::mock(Database::class);
        $database->shouldReceive('select')->andReturn($database);
        $database->shouldReceive('from')->andReturn($database);
        $database->shouldReceive('get')->andReturn(EntityData::getDelivery());

        $this->deliveryRepository = new Deliveries('', Delivery::class, $database);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testCalculateDeliveryFromValue(): void
    {
        $value = $this->deliveryRepository->calculateDeliveryFromValue(9.99);

        $this->assertEquals(10, $value);

        $value = $this->deliveryRepository->calculateDeliveryFromValue(11.99);

        $this->assertEquals(5, $value);

        $value = $this->deliveryRepository->calculateDeliveryFromValue(31.99);

        $this->assertEquals(0, $value);
    }
}
