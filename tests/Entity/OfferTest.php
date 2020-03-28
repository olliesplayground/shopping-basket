<?php


namespace App\Test\Entity;


use App\Entity\Offer;
use PHPUnit\Framework\TestCase;

class OfferTest extends TestCase
{
    /**
     * @var Offer
     */
    protected $offer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->offer = new Offer(1, ['P1', 'p2'], 0.5, 'test');
    }

    public function testGetters(): void
    {
        $id = $this->offer->getId();
        $products = $this->offer->getProducts();
        $adjustment = $this->offer->getAdjustment();
        $adjustmentType = $this->offer->getAdjustmentType();

        $this->assertEquals(1, $id);
        $this->assertCount(2, $products);
        $this->assertEquals(0.5, $adjustment);
        $this->assertEquals('test', $adjustmentType);
    }
}
