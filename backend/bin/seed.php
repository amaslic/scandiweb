<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use PDO;

class Seeder
{
    private PDO $pdo;

    public function __construct()
    {
        $dsn = 'mysql:host=localhost;dbname=scandiweb;charset=utf8mb4';
        $user = 'root';
        $pass = '';

        $this->pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }

    public function createTables(): void
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS categories (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL UNIQUE
            );
        ");

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

        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS attributes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                product_id INT NOT NULL,
                name VARCHAR(255) NOT NULL,
                value VARCHAR(255) NOT NULL,
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
            );
        ");
    }

    public function run(string $jsonFilePath): void
    {
        $this->createTables();

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

        // Insert products and attributes
        foreach ($products as $product) {
            $categoryId = $categoryMap[$product['category']] ?? null;

            if (!$categoryId) {
                continue;
            }

            $stmt = $this->pdo->prepare("INSERT INTO products (sku, name, price, category_id) VALUES (:sku, :name, :price, :category_id)");
            $stmt->execute([
                'sku' => $product['sku'],
                'name' => $product['name'],
                'price' => $product['price'],
                'category_id' => $categoryId,
            ]);

            $productId = $this->pdo->lastInsertId();

            foreach ($product['attributes'] as $attribute) {
                $stmt = $this->pdo->prepare("INSERT INTO attributes (product_id, name, value) VALUES (:product_id, :name, :value)");
                $stmt->execute([
                    'product_id' => $productId,
                    'name' => $attribute['name'],
                    'value' => $attribute['value'],
                ]);
            }
        }

        echo "✅ Tabele kreirane i podaci ubačeni uspešno.\n";
    }

    private function getCategoryIdByName(string $name): int
    {
        $stmt = $this->pdo->prepare("SELECT id FROM categories WHERE name = :name");
        $stmt->execute(['name' => $name]);
        return (int) ($stmt->fetchColumn() ?? 0);
    }
}

$seeder = new Seeder();
$seeder->run(__DIR__ . '../data/data.json');
