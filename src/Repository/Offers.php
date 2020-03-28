<?php


namespace App\Repository;


use App\Entity\Offer;
use App\Entity\Product;

class Offers extends Repository
{
    /**
     * @param $object
     * @return Offer
     */
    protected function translate($object): Offer
    {
        $object = $this->convertToArray($object);
        return new $this->entity( $object['id'], $object['products'], $object['adjustment'], $object['adjustmentType'] );
    }

    /**
     * @param array $products
     * @return float
     */
    public function calculateOfferValue(array $products): float
    {
        $offers = $this->get();

        $totalReduction = 0;

        /** @var Offer $offer */
        foreach ($offers as $offer) {
            $totalReduction += $this->checkAndCalculateOffer($offer, $products);
        }

        return $totalReduction;
    }

    /**
     * @param Offer $offer
     * @param array $products
     * @return float
     */
    private function checkAndCalculateOffer(Offer $offer, array $products): float
    {
        $offerProducts = $offer->getProducts();

        $productCodes = [];
        $productEntities = [];

        /**
         * Compile an array of product codes &
         * an array product entities
         * @var Product $product
         */
        foreach ($products as $product) {
            $productCodes[] = $product->getCode();
            $productEntities[$product->getCode()] = $product;
        }

        $groupedProducts = $this->groupArrayValues($productCodes);
        $groupedOfferProducts = $this->groupArrayValues($offerProducts);

        $matchedProducts = [];

        foreach ($groupedOfferProducts as $productCode => $offerProductCount) {
            $basketProductCount = $groupedProducts[$productCode] ?? 0;

            if ($basketProductCount >= $offerProductCount) {
                $matchedProducts[$productCode] = $basketProductCount / $offerProductCount;
            }
        }

        $matchedProductsCount = count($matchedProducts);

        if ($matchedProductsCount > 0 && $matchedProductsCount === count($groupedOfferProducts)) {
            /**
             * Compile an array of product entities for this offer
             */
            $applicableProducts = [];

            foreach ($offerProducts as $productCode) {
                $applicableProducts[] = $productEntities[$productCode];
            }

            /**
             * The number of times this offer applies to the products
             */
            $multiple = floor(min($matchedProducts));

            return $this->calculateOfferReduction($offer, $applicableProducts, $multiple);
        }

        return 0;
    }

    /**
     * @param Offer $offer
     * @param array $products
     * @param int $multiple
     * @return float
     */
    private function calculateOfferReduction(Offer $offer, array $products, int $multiple): float
    {
        $productTotal = array_reduce($products, static function($total, Product $product) {
            $total += $product->getPrice();
            return $total;
        });

        $reduction = 0;

        switch ($offer->getAdjustmentType()) {
            case 'percentage':
                $reduction = ( $productTotal - ($productTotal * $offer->getAdjustment()) ) * $multiple;
                break;
            case 'fixed':
                $reduction = $offer->getAdjustment() * $multiple;
                break;
        }

        return $reduction;
    }

    /**
     * @param array $arrayToGroup
     * @return array
     */
    private function groupArrayValues(array $arrayToGroup): array
    {
        $groups = [];

        foreach ($arrayToGroup as $item) {
            if (!array_key_exists($item, $groups)) {
                $groups[$item] = 0;
            }

            $groups[$item]++;
        }

        return $groups;
    }
}
