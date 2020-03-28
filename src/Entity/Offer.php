<?php


namespace App\Entity;


class Offer
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var array
     */
    private $products;

    /**
     * @var float
     */
    private $adjustment;

    /**
     * @var string
     */
    private $adjustmentType;

    /**
     * Offer constructor.
     * @param int $id
     * @param array $products
     * @param float $adjustment
     * @param string $adjustmentType
     */
    public function __construct(int $id, array $products, float $adjustment, string $adjustmentType)
    {
        $this->id = $id;
        $this->products = $products;
        $this->adjustment = $adjustment;
        $this->adjustmentType = $adjustmentType;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @return float
     */
    public function getAdjustment(): float
    {
        return $this->adjustment;
    }

    /**
     * @return string
     */
    public function getAdjustmentType(): string
    {
        return $this->adjustmentType;
    }
}
