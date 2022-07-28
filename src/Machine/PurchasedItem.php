<?php

namespace App\Machine;

/**
 * Class PurchasedItem
 * @package App\Machine
 */
class PurchasedItem implements PurchasedItemInterface
{
    /**
     * @var int
     */
    private $quantity;

    /**
     * @var float
     */
    private $amount;

    /**
     * @var float
     */
    private $packPrice;

    /**
     * PurchasedItem constructor.
     *
     * @param int $quantity
     * @param float $amount
     * @param float $packPrice
     */
    public function __construct(int $quantity, float $amount, float $packPrice)
    {
        $this->quantity = $quantity;
        $this->amount = $amount;
        $this->packPrice = $packPrice;
    }

    /**
     * @return int
     */
    public function getItemQuantity()
    {
        // Quantity remains 0 If the provided quantity is 0 or provided amount is less then static pack price
        if (!$this->quantity || $this->amount < $this->packPrice) {
            return $this->quantity = 0;
        }

        $currentQuantity = intval($this->amount / $this->packPrice);
        if ($currentQuantity > $this->quantity) {
            $currentQuantity = $currentQuantity - $this->quantity;
        }
        $this->quantity = $currentQuantity;

        return $this->quantity;
    }

    /**
     * @return float
     */
    public function getTotalAmount()
    {
        // Calculate total amount based on valid quantity
        if ($this->quantity) {
            return floatval($this->quantity * $this->packPrice);
        }

        return $this->amount;
    }

    /**
     * @return array
     */
    public function getChange()
    {
        $usedAmount = $this->getTotalAmount();
        $remainingAmount = floatval(number_format($this->amount - $usedAmount, 2));
        if ($usedAmount < 1 || $remainingAmount <= 0) {
            return [['0.00', '0']];
        }

        $coins = $this->manageChange($remainingAmount); // Manage coins based on remaining amount
        $change = [];
        foreach ($coins as $coin => $count) {
            $change[] = [$coin, $count];
        }

        return $change;
    }

    /**
     * @param float $amount
     * @return array
     */
    private function manageChange(float $amount)
    {
        $coins = [200, 100, 50, 20, 10, 5, 2, 1]; // Represent cents in an euro and 100 or 200 means 1 or 2 euro
        $amount = $amount * 100; // Convert into decimals
        $response = [];
        while ($amount >= 1) {
            foreach ($coins as $coin) {
                if ($coin <= $amount) {
                    $response[] = number_format($coin / 100, 2); // Convert into fraction
                    $amount = $amount - $coin; // Set remaining amount
                    break;
                }
            }
        }

        return array_count_values($response);
    }
}
