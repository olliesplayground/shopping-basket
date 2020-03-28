<?php
// Basket products

$products = ['R01', 'G01'];

// Offer Products

$offers = [
//    ['id' => 1, 'products' => ['R01', 'R01']],
    ['id' => 2, 'products' => ['G01', 'R01']]
];

$applicableOffers = [];

foreach ($offers as $offer) {
    $offerProducts = $offer['products'];

    $offerProductsCopy = $offerProducts;

    foreach ($offerProducts as $offerProductsKey =>  $offerProduct) {

        foreach ($products as $product) {
            echo $offer['id'] . ' - ' . $offerProduct . ' : ' . $product . PHP_EOL;
            if ($offerProduct === $product) {

                unset($offerProductsCopy[$offerProductsKey]);

                print_r($offerProductsCopy);

                if (empty($offerProductsCopy)) {

                    $applicableOffers[] = $offer;

                    $offerProductsCopy = $offerProducts;
                }
                break 2;
            }
        }
    }
}

echo count($applicableOffers) . PHP_EOL;

// print_r($applicableOffers);
