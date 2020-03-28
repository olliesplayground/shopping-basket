<?php


namespace App\Entity;


class Delivery
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var float
     */
    private $lower;

    /**
     * @var float
     */
    private $upper;

    /**
     * @var float
     */
    private $cost;

    /**
     * Delivery constructor.
     * @param int $id
     * @param float $lower
     * @param float|null $upper
     * @param float $cost
     */
    public function __construct(int $id, float $lower, ?float $upper, float $cost)
    {
        $this->id = $id;
        $this->lower = $lower;
        $this->upper = $upper;
        $this->cost = $cost;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getLower(): float
    {
        return $this->lower;
    }

    /**
     * @return float|null
     */
    public function getUpper(): ?float
    {
        return $this->upper;
    }

    /**
     * @return float
     */
    public function getCost(): float
    {
        return $this->cost;
    }
}
