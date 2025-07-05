<?php

declare(strict_types=1);

namespace App\Models\Price;

/**
 * Base abstract class for Price models.
 */
abstract class AbstractPrice
{
    protected float $amount;
    protected string $currencyLabel;
    protected string $currencySymbol;

    public function __construct(float $amount, string $currencyLabel, string $currencySymbol)
    {
        $this->amount = $amount;
        $this->currencyLabel = $currencyLabel;
        $this->currencySymbol = $currencySymbol;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrencyLabel(): string
    {
        return $this->currencyLabel;
    }

    public function getCurrencySymbol(): string
    {
        return $this->currencySymbol;
    }
}
