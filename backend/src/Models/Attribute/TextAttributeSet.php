<?php

declare(strict_types=1);

namespace App\Models\Attribute;

/**
 * Text-based attribute set implementation.
 */
class TextAttributeSet extends AbstractAttributeSet
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
                $row['id'],
                $row['value'],
                $row['display_value']
            );
        }
    }
}
