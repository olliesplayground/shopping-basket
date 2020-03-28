<?php


namespace App\Test\Data;


class EntityData
{
    public static function getProducts(): array
    {
        return [
            ['code' => 'P1', 'name' => 'Product 1', 'price' => 10],
            ['code' => 'P2', 'name' => 'Product 2', 'price' => 15],
            ['code' => 'P3', 'name' => 'Product 3', 'price' => 19.99]
        ];
    }

    public static function getOffers(): array
    {
        return [
            ['id' => 1, 'products' => ['P1', 'P3'], 'adjustment' => 0.75, 'adjustmentType' => 'percentage'],
            ['id' => 2, 'products' => ['P1'], 'adjustment' => 5, 'adjustmentType' => 'fixed']
        ];
    }

    public static function getDelivery(): array
    {
        return [
            ['id' => 1, 'lower' => 0, 'upper' => 10, 'cost' => 10],
            ['id' => 2, 'lower' => 11, 'upper' => 20, 'cost' => 5],
            ['id' => 2, 'lower' => 21, 'upper' => null, 'cost' => 0]
        ];
    }
}
