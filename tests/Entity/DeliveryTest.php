<?php


namespace App\Test\Entity;


use App\Entity\Delivery;
use PHPUnit\Framework\TestCase;

class DeliveryTest extends TestCase
{
    /**
     * @var Delivery
     */
    protected $delivery;

    protected function setUp(): void
    {
        parent::setUp();

        $this->delivery = new Delivery(1, 0, 0.5, 0.99);
    }

    public function testGetters(): void
    {
        $id = $this->delivery->getId();
        $lower = $this->delivery->getLower();
        $upper = $this->delivery->getUpper();
        $cost = $this->delivery->getCost();

        $this->assertEquals(1, $id);
        $this->assertEquals(0, $lower);
        $this->assertEquals(0.5, $upper);
        $this->assertEquals(0.99, $cost);
    }
}
