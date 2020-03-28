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
        $offerProductsCopy = $offerProducts;

        $applicableProducts = [];
        $reduction = 0;

        /**
         * @var Product $product
         */
        foreach ($products as $product) {

            $productCode = $product->getCode();

            foreach ($offerProductsCopy as $offerProductKey => $offerProduct) {

                if($offerProduct === $productCode) {

                    $applicableProducts[] = $product;

                    unset($offerProductsCopy[$offerProductKey]);

                    if ( empty($offerProductsCopy) ) {
                        $reduction += $this->calculateOfferReduction($offer, $applicableProducts);
                        $offerProductsCopy = $offerProducts;
                        $applicableProducts = [];
                    }

                    break;
                }
            }
        }

        return $reduction;
    }

    /**
     * @param Offer $offer
     * @param array $products
     * @return float
     */
    private function calculateOfferReduction(Offer $offer, array $products): float
    {
        $productTotal = array_reduce($products, static function($total, Product $product) {
            $total += $product->getPrice();
            return $total;
        });

        $reduction = 0;

        switch ($offer->getAdjustmentType()) {
            case 'percentage':
                $reduction = $productTotal - ($productTotal * $offer->getAdjustment());
                break;
            case 'value':
                $reduction = $offer->getAdjustment();
                break;
        }

        return $reduction;
    }
}
