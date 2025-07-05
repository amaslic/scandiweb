<?php

declare(strict_types=1);

namespace App\Models\Product;

use App\Models\Product\AbstractProduct;
use App\Models\Product\SimpleProduct;
use App\Database\Database;
use Doctrine\DBAL\Connection;

/**
 * Factory to instantiate the correct Product subclass based on the product's type.
 */
class ProductFactory
{

    /**
     * Create an AbstractProduct instance for the given product ID.
     *
     * @param int $id
     * @return AbstractProduct
     */
    public static function create(int $id, ?Connection $connection = null): AbstractProduct
    {
        $connection ??= Database::getConnection();
        return new SimpleProduct($id, $connection);
    }
}
