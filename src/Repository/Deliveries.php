<?php


namespace App\Repository;


use App\Entity\Delivery;

class Deliveries extends Repository
{
    /**
     * @param $object
     * @return Delivery
     */
    protected function translate($object): Delivery
    {
        $object = $this->convertToArray($object);
        return new $this->entity( $object['id'], $object['lower'], $object['upper'], $object['cost'] );
    }

    /**
     * @param float $value
     * @return float
     */
    public function calculateDeliveryFromValue(float $value): float
    {
        $deliveries = $this->get();

        /** @var Delivery $delivery */
        foreach ($deliveries as $delivery) {
            if ($value >= $delivery->getLower() && $value < $delivery->getUpper()) {
                return $delivery->getCost();
            }
        }

        return 0;
    }
}
