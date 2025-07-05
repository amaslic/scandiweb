<?php

declare(strict_types=1);

namespace App\Models\Attribute;

use App\Database\Database;
use InvalidArgumentException;

/**
 * Factory to instantiate the correct AttributeSet subclass for a given product and attribute.
 */
class AttributeSetFactory
{
    /**
     * Create an AbstractAttributeSet instance based on the attribute's metadata.
     *
     * @param int $productId
     * @param int $attributeId
     * @return AbstractAttributeSet
     */
    public static function create(int $productId, int $attributeId): AbstractAttributeSet
    {
        $conn = Database::getConnection();

        // Fetch attribute metadata (name and type)
        $qb = $conn->createQueryBuilder();
        $row = $qb->select('name', 'type')
            ->from('attributes')
            ->where('id = :aid')
            ->setParameter('aid', $attributeId)
            ->executeQuery()
            ->fetchAssociative();

        if (!$row) {
            throw new InvalidArgumentException("Attribute with ID {$attributeId} not found.");
        }

        $name = (string) $row['name'];
        $type = (string) $row['type'];

        // Determine the correct AttributeSet class
        return match ($type) {
            'text' => new TextAttributeSet($conn, $productId, $name),
            'swatch' => new SwatchAttributeSet($conn, $productId, $name),
            default => throw new InvalidArgumentException("Unsupported attribute type '{$type}' for '{$name}'."),
        };
    }
}
