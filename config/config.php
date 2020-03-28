<?php

$config['data_dir'] = APPPATH . '/data';

$config['repositories'] = [
    'products' => [
        'repository' => \App\Repository\Products::class,
        'entity' => \App\Entity\Product::class,
        'file' => 'products.json'
    ],
    'deliveries' => [
        'repository' => \App\Repository\Deliveries::class,
        'entity' => \App\Entity\Delivery::class,
        'file' => 'delivery.json'
    ],
    'offers' => [
        'repository' => \App\Repository\Offers::class,
        'entity' => \App\Entity\Offer::class,
        'file' => 'offers.json'
    ]
];
