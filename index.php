<?php

require __DIR__ . '/vendor/autoload.php';

use App\Services\Config\Config;
use App\Services\Database\Database;
use App\Repository\RepositoryManager;
use App\Services\Basket\Basket;


define('APPPATH', __DIR__);

$config = Config::getConfig();

$basket = new Basket(
    new RepositoryManager(
        $config['repositories'],
        new Database( $config['data_dir'] )
    )
);

$tests = [
    [
        'products' => ['B01', 'G01'],
        'expected' => 37.85
    ],
    [
        'products' => ['R01', 'R01'],
        'expected' => 54.37
    ],
    [
        'products' => ['R01', 'G01'],
        'expected' => 60.85
    ],
    [
        'products' => ['B01', 'B01', 'R01', 'R01', 'R01'],
        'expected' => 98.27
    ]
];

$mask = "| %-25s | %-20s | %-20s | %-10s |\n";
printf($mask, 'Products', 'Expected Result', 'Actual Result', 'Match');

foreach ($tests as $test) {
    $basket->clearBasket();

    foreach ($test['products'] as $product) {
        $basket->addProduct($product);
    }

    $total = $basket->getTotal();

    printf($mask, implode(',', $test['products']), $test['expected'], $total, ($test['expected'] === $total));
}
