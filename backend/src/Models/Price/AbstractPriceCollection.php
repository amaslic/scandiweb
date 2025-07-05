<?php

declare(strict_types=1);

namespace App\Models\Price;

use Doctrine\DBAL\Connection;
use App\Database\Database;

/**
 * Base abstract class for price collections of a product.
 * Loads price entries for a given product.
 */
abstract class AbstractPriceCollection
{
    protected Connection $connection;
    protected int $productId;

    /** @var Price[] */
    protected array $prices = [];

    public function __construct(Connection $connection, int $productId)
    {
        $this->connection = $connection;
        $this->productId = $productId;
        $this->loadPrices();
    }

    abstract protected function loadPrices(): void;

    /**
     * @return Price[]
     */
    public function getPrices(): array
    {
        return $this->prices;
    }

    public function getByCurrency(string $currencyLabel): ?Price
    {
        foreach ($this->prices as $price) {
            if ($price->getCurrency()->getLabel() === $currencyLabel) {
                return $price;
            }
        }

        return null;
    }

    protected function addPrice(Price $price): void
    {
        $this->prices[] = $price;
    }
}
