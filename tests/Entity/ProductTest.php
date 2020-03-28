<?php


namespace App\Test\Entity;


use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    /**
     * @var Product
     */
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = new Product('TEST', 'NAME', 0.99);
    }

    public function testGetters(): void
    {
        $code = $this->product->getCode();
        $name = $this->product->getName();
        $price = $this->product->getPrice();

        $this->assertEquals('TEST', $code);
        $this->assertEquals('NAME', $name);
        $this->assertEquals(0.99, $price);
    }
}
