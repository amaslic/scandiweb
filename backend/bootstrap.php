<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use App\Database\Database;

// 1) Load .env into $_ENV and $_SERVER
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// 2) Read vars first from $_ENV, then from getenv()
$envHost = $_ENV['DB_HOST'] ?? getenv('DB_HOST');
$envPort = $_ENV['DB_PORT'] ?? getenv('DB_PORT');
$envName = $_ENV['DB_NAME'] ?? getenv('DB_NAME');
$envUser = $_ENV['DB_USER'] ?? getenv('DB_USER');
$envPass = $_ENV['DB_PASS'] ?? getenv('DB_PASS');

// 3) Initialize your DB
Database::init([
    'host'     => $envHost,
    'port'     => $envPort,
    'dbname'   => $envName,
    'user'     => $envUser,
    'password' => $envPass,
]);