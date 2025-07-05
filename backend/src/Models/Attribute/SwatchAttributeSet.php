<?php

declare(strict_types=1);

namespace App\Models\Attribute;

/**
 * Swatch attribute set implementation.
 */
class SwatchAttributeSet extends AbstractAttributeSet
{
    protected function loadValues(): void
    {
        $qb = $this->connection->createQueryBuilder();
        $rows = $qb
            ->select('av.value', 'av.display_value', 'av.id')
            ->from('attribute_values', 'av')
            ->join('av', 'attributes', 'a', 'av.attribute_id = a.id')
            ->where('av.product_id = :pid')
            ->andWhere('a.name = :name')
            ->setParameter('pid', $this->productId)
            ->setParameter('name', $this->name)
            ->executeQuery()
            ->fetchAllAssociative();

        foreach ($rows as $row) {
            $this->values[] = new AttributeItem(
                (int) $row['id'],
                (string) $row['value'],
                $row['display_value'] !== null ? (string) $row['display_value'] : null
            );
        }
    }
}
