<?php


namespace App\Repository;


use App\Entity\Product;

class Products extends Repository
{
    /**
     * @var string
     */
    protected $primaryKey = 'code';

    protected function translate($object): Product
    {
        return new $this->entity( $object['code'], $object['name'], $object['price'] );
    }
}
