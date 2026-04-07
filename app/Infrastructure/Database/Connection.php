<?php

declare(strict_types=1);

namespace App\Infrastructure\Database;

use PDO;
use PDOException;
use RuntimeException;

final class Connection
{
    public static function make(): PDO
    {
        $dsn = $_ENV['DB_DSN'] ?? self::defaultDsn();
        $user = $_ENV['DB_USER'] ?? null;
        $password = $_ENV['DB_PASSWORD'] ?? null;

        self::ensureSqliteDirectoryExists($dsn);

        try {
            return new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $exception) {
            throw new RuntimeException('Database connection failed', 0, $exception);
        }
    }

    public static function defaultDsn(): string
    {
        return 'sqlite:' . dirname(__DIR__, 3) . '/var/database/app.sqlite';
    }

    private static function ensureSqliteDirectoryExists(string $dsn): void
    {
        if (!str_starts_with($dsn, 'sqlite:')) {
            return;
        }

        $path = substr($dsn, 7);

        if ($path === ':memory:' || $path === '') {
            return;
        }

        $directory = dirname($path);

        if (is_dir($directory)) {
            return;
        }

        if (!mkdir($directory, 0775, true) && !is_dir($directory)) {
            throw new RuntimeException('Unable to create SQLite directory');
        }
    }
}