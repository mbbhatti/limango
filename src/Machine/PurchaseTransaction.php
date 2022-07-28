<?php

namespace App\Machine;

/**
 * Class PurchaseTransaction
 * @package App\Machine
 */
class PurchaseTransaction implements PurchaseTransactionInterface
{
    /**
     * @var int
     */
    private $itemQuantity;

    /**
     * @var float
     */
    private $amount;

    /**
     * PurchaseTransaction constructor.
     *
     * @param int $itemQuantity
     * @param float $amount
     */
    public function __construct(int $itemQuantity, float $amount)
    {
        $this->itemQuantity = $itemQuantity;
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getItemQuantity()
    {
        return $this->itemQuantity;
    }

    /**
     * @return float
     */
    public function getPaidAmount()
    {
        return $this->amount;
    }
}
