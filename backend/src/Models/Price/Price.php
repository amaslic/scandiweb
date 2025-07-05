<?php

declare(strict_types=1);

namespace App\Models\Price;
use App\Models\Currency\Currency;

/**
 * Represents a single price entry for a product.
 */
class Price
{
    private float $amount;

    public function __construct(float $amount, private Currency $currency)
    {
        $this->amount = $amount;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }
}
