<?php

declare(strict_types=1);

namespace App\Models\Price;

use App\Models\Currency\Currency;

class PriceCollection extends AbstractPriceCollection
{
    /**
     * Load price entries from the database into $this->prices.
     *
     * @return void
     */
    protected function loadPrices(): void
    {
        $qb = $this->connection->createQueryBuilder();
        $rows = $qb->select('amount', 'currency_label', 'currency_symbol')
            ->from('prices')
            ->where('product_id = :pid')
            ->setParameter('pid', $this->productId)
            ->executeQuery()
            ->fetchAllAssociative();

        foreach ($rows as $row) {
            $currency = new Currency(
                (string) $row['currency_label'],
                (string) $row['currency_symbol']
            );

            $this->prices[] = new Price(
                (float) $row['amount'],
                $currency
            );
        }
    }
}
