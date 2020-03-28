<?php


namespace App\Services\Basket;

use App\Repository\Deliveries;
use App\Repository\Offers;
use App\Repository\RepositoryManager;
use App\Entity\Product;

/**
 * Class Basket
 * @package App\Services\Basket
 */
class Basket
{
    /**
     * @var RepositoryManager
     */
    private $repositoryManager;

    /**
     * @var array
     */
    private $productsInBasket = [];

    /**
     * Basket constructor.
     * @param RepositoryManager $repositoryManager
     */
    public function __construct(RepositoryManager $repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
    }

    /**
     * Add a product to the basket
     * @param string $productCode
     * @return bool
     */
    public function addProduct(string $productCode): bool
    {
        $productRepository = $this->repositoryManager->getRepository('products');

        /**  @var Product $product */
        if ( ( $product = $productRepository->getOne($productCode) ) === null ) {
            return false;
        }

        $this->productsInBasket[] = $product;

        return true;
    }

    /**
     * Get the basket total
     * @return float
     */
    public function getTotal(): float
    {
        return round($this->calculateTotal(), 2, PHP_ROUND_HALF_DOWN) ;
    }

    /**
     * Clear the basket of products
     */
    public function clearBasket(): void
    {
        $this->productsInBasket = [];
    }

    /**
     * Calculate the basket total
     * @return float
     */
    protected function calculateTotal(): float
    {
        if ( count( $this->productsInBasket ) === 0 ) {
            return 0;
        }

        $total = array_reduce($this->productsInBasket, static function ($carry, Product $product) {
            $carry += $product->getPrice();
            return $carry;
        });

        /** @var Offers $offersRepository */
        $offersRepository = $this->repositoryManager->getRepository('offers');

        /** Apply offers first */
        $total -= $offersRepository->calculateOfferValue($this->productsInBasket);

        /** @var Deliveries $deliveryRepository */
        $deliveryRepository = $this->repositoryManager->getRepository('deliveries');

        /** Then apply delivery */
        return $total + $deliveryRepository->calculateDeliveryFromValue($total);
    }
}
