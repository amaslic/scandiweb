<?php

namespace App\Models\Currency;

class Currency
{
    public function __construct(
        private string $label,
        private string $symbol
    ) {
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }
}
