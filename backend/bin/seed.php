<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

class Seeder
{
    private PDO $pdo;

    public function __construct()
    {
        echo "Connecting...\n";

        $host = $_ENV['DB_HOST'];
        $port = $_ENV['DB_PORT'];
        $db = $_ENV['DB_NAME'];
        $user = $_ENV['DB_USER'];
        $pass = $_ENV['DB_PASS'];

        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

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
                in_stock BOOLEAN NOT NULL DEFAULT TRUE,
                description TEXT,
                brand VARCHAR(255),
                FOREIGN KEY (category_id) REFERENCES categories(id)
            );
        ");
        echo "Table 'products' created.\n";

        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS product_gallery (
                id INT AUTO_INCREMENT PRIMARY KEY,
                product_id INT NOT NULL,
                image_url TEXT NOT NULL,
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
            );
        ");
        echo "Table 'product_gallery' created.\n";

        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS attributes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL UNIQUE
            );
        ");
        echo "Table 'attributes' created.\n";

        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS attribute_values (
                id INT AUTO_INCREMENT PRIMARY KEY,
                product_id INT NOT NULL,
                attribute_id INT NOT NULL,
                value VARCHAR(255) NOT NULL,
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
                FOREIGN KEY (attribute_id) REFERENCES attributes(id) ON DELETE CASCADE
            );
        ");
        echo "Table 'attribute_values' created.\n";
    }

    public function run(string $jsonFilePath): void
    {
        $this->createTables();

        echo "Loading json data...\n";
        $data = json_decode(file_get_contents($jsonFilePath), true);

        $categories = $data['data']['categories'] ?? [];
        $products = $data['data']['products'] ?? [];

        $categoryMap = [];

        foreach ($categories as $category) {
            $stmt = $this->pdo->prepare("INSERT IGNORE INTO categories (name) VALUES (:name)");
            $stmt->execute(['name' => $category['name']]);
            $categoryMap[$category['name']] = $this->pdo->lastInsertId() ?: $this->getCategoryIdByName($category['name']);
        }

        echo "Categories created: " . count($categoryMap) . "\n";

        $productCount = 0;
        foreach ($products as $product) {
            $sku = $product['id'] ?? null;
            $name = $product['name'] ?? null;
            $category = $product['category'] ?? null;
            $price = $product['prices'][0]['amount'] ?? null;
            $inStock = $product['inStock'] ?? true;
            $description = $product['description'] ?? null;
            $brand = $product['brand'] ?? null;

            if (!$sku || !$name || !$category || $price === null) {
                continue;
            }

            $categoryId = $categoryMap[$category] ?? null;
            if (!$categoryId) {
                continue;
            }

            $stmt = $this->pdo->prepare("
                INSERT INTO products (sku, name, price, category_id, in_stock, description, brand)
                VALUES (:sku, :name, :price, :category_id, :in_stock, :description, :brand)
            ");
            $stmt->execute([
                'sku' => $sku,
                'name' => $name,
                'price' => $price,
                'category_id' => $categoryId,
                'in_stock' => $inStock,
                'description' => $description,
                'brand' => $brand,
            ]);

            $productId = $this->pdo->lastInsertId();
            $productCount++;

            // Insert gallery images
            foreach ($product['gallery'] ?? [] as $imageUrl) {
                $stmt = $this->pdo->prepare("INSERT INTO product_gallery (product_id, image_url) VALUES (:product_id, :image_url)");
                $stmt->execute([
                    'product_id' => $productId,
                    'image_url' => $imageUrl,
                ]);
            }

            // Insert attribute values
            foreach ($product['attributes'] ?? [] as $attribute) {
                $attrName = $attribute['name'];

                $stmt = $this->pdo->prepare("SELECT id FROM attributes WHERE name = :name");
                $stmt->execute(['name' => $attrName]);
                $attributeId = $stmt->fetchColumn();

                if (!$attributeId) {
                    $stmt = $this->pdo->prepare("INSERT INTO attributes (name) VALUES (:name)");
                    $stmt->execute(['name' => $attrName]);
                    $attributeId = $this->pdo->lastInsertId();
                }

                foreach ($attribute['items'] as $item) {
                    $stmt = $this->pdo->prepare("
                        INSERT INTO attribute_values (product_id, attribute_id, value)
                        VALUES (:product_id, :attribute_id, :value)
                    ");
                    $stmt->execute([
                        'product_id' => $productId,
                        'attribute_id' => $attributeId,
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
