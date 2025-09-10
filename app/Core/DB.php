<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

class DB
{
    private static ?PDO $instance = null;

    public static function get(): PDO
    {
        if (self::$instance === null) {
            $dsn = sprintf('%s:host=%s;port=%s;dbname=%s;charset=%s',
                $_ENV['DB_DRIVER'] ?? 'mysql',
                $_ENV['DB_HOST'] ?? '127.0.0.1',
                $_ENV['DB_PORT'] ?? '3306',
                $_ENV['DB_DATABASE'] ?? '',
                $_ENV['DB_CHARSET'] ?? 'utf8mb4'
            );
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            try {
                self::$instance = new PDO($dsn, $_ENV['DB_USER'] ?? '', $_ENV['DB_PASSWORD'] ?? '', $options);
                if (isset($_ENV['DB_TIMEZONE'])) {
                    self::$instance->exec("SET time_zone = '" . addslashes($_ENV['DB_TIMEZONE']) . "'");
                }
            } catch (PDOException $e) {
                throw $e;
            }
        }
        return self::$instance;
    }
}
