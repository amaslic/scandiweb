<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

class Seeder
{
    private PDO $pdo;

    public function __construct()
    {
        echo "Connecting...\n";

        $dsn = 'mysql:host=127.0.0.1;port=3306;dbname=scandiweb;charset=utf8mb4';
        $user = 'root';
        $pass = '';

        $this->pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        echo "Connected.\n";
    }

    public function createTables(): void
    {
        echo "Creating tables...\n";

        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS categories (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL UNIQUE
            );
        ");
        echo "Table 'categories' created.\n";

        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS products (
                id INT AUTO_INCREMENT PRIMARY KEY,
                sku VARCHAR(255) NOT NULL UNIQUE,
                name VARCHAR(255) NOT NULL,
                price DECIMAL(10,2) NOT NULL,
                category_id INT,
                FOREIGN KEY (category_id) REFERENCES categories(id)
            );
        ");
        echo "Table 'products' created.\n";

        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS attributes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                product_id INT NOT NULL,
                name VARCHAR(255) NOT NULL,
                value VARCHAR(255) NOT NULL,
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
            );
        ");
        echo "Table 'attributes' created.\n";
    }

    public function run(string $jsonFilePath): void
    {
        $this->createTables();

        echo "Loading json data...\n";
        $data = json_decode(file_get_contents($jsonFilePath), true);

        $categories = $data['data']['categories'] ?? [];
        $products = $data['data']['products'] ?? [];

        $categoryMap = [];

        // Insert categories
        foreach ($categories as $category) {
            $stmt = $this->pdo->prepare("INSERT IGNORE INTO categories (name) VALUES (:name)");
            $stmt->execute(['name' => $category['name']]);
            $categoryMap[$category['name']] = $this->pdo->lastInsertId() ?: $this->getCategoryIdByName($category['name']);
        }

        echo "Categories created " . count($categoryMap) . "\n";

        // Insert products and attributes
        $productCount = 0;
        foreach ($products as $product) {
            $sku = $product['id'] ?? null;
            $name = $product['name'] ?? null;
            $category = $product['category'] ?? null;
            $price = $product['prices'][0]['amount'] ?? null;

            if (!$sku || !$name || !$category || $price === null) {
                continue;
            }

            $categoryId = $categoryMap[$category] ?? null;
            if (!$categoryId) {
                continue;
            }

            $stmt = $this->pdo->prepare("INSERT INTO products (sku, name, price, category_id) VALUES (:sku, :name, :price, :category_id)");
            $stmt->execute([
                'sku' => $sku,
                'name' => $name,
                'price' => $price,
                'category_id' => $categoryId,
            ]);

            $productId = $this->pdo->lastInsertId();
            $productCount++;

            foreach ($product['attributes'] as $attribute) {
                $attrName = $attribute['name'];
                foreach ($attribute['items'] as $item) {
                    $stmt = $this->pdo->prepare("INSERT INTO attributes (product_id, name, value) VALUES (:product_id, :name, :value)");
                    $stmt->execute([
                        'product_id' => $productId,
                        'name' => $attrName,
                        'value' => $item['value'],
                    ]);
                }
            }
        }

        echo "Products created: $productCount\n";
        echo "Seeded.\n";
    }

    private function getCategoryIdByName(string $name): int
    {
        $stmt = $this->pdo->prepare("SELECT id FROM categories WHERE name = :name");
        $stmt->execute(['name' => $name]);
        return (int) ($stmt->fetchColumn() ?? 0);
    }
}

$seeder = new Seeder();
$seeder->run(__DIR__ . '/data.json');
