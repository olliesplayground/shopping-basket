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

?>
<html lang="en">
<head>
    <title>Shopping Basket</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <table style="width: 100%;">
        <tr>
            <th>Products</th>
            <th>Expected Result</th>
            <th>Actual Result</th>
            <th>Match</th>
        </tr>
        <?php
        $mask = "\t<tr>\n\t\t<td>%s</td>\n\t\t<td>%s</td>\n\t\t<td>%s</td>\n\t\t<td>%s</td>\n\t</tr>\n";
        foreach ($tests as $test) {
            $basket->clearBasket();

            foreach ($test['products'] as $product) {
                $basket->addProduct($product);
            }

            $total = $basket->getTotal();

            printf($mask, implode(',', $test['products']), $test['expected'], $total, ($test['expected'] === $total));
        }
        ?>
    </table>
</body>
</html>
