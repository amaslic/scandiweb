<?php

namespace App\Repository;

use App\Model\AttributeValue;
use App\Model\Attribute;
use PDO;

class AttributeValueRepository
{
    private PDO $pdo;
    private AttributeRepository $attributeRepo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->attributeRepo = new AttributeRepository($pdo);
    }

    /**
     * Get all attribute values for a specific product.
     *
     * @param int $productId
     * @return AttributeValue[]
     */
    public function findByProductId(int $productId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT id, attribute_id, value 
            FROM attribute_values 
            WHERE product_id = :product_id
        ");
        $stmt->execute(['product_id' => $productId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $values = [];

        foreach ($rows as $row) {
            $attribute = $this->attributeRepo->findById((int) $row['attribute_id']);
            if ($attribute) {
                $values[] = new AttributeValue(
                    (int) $row['id'],
                    $productId,
                    $attribute,
                    $row['value']
                );
            }
        }

        return $values;
    }
}
