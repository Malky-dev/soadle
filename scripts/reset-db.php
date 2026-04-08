<?php

declare(strict_types=1);

require_once __DIR__ . '/../bootstrap/app.php';

use App\Infrastructure\Database\Connection;

$dbDsn = $_ENV['DB_DSN'] ?? Connection::defaultDsn();

if (!str_starts_with($dbDsn, 'sqlite:')) {
    fwrite(STDERR, "Reset is only supported for SQLite databases.\n");
    exit(1);
}

$dbPath = substr($dbDsn, 7);

if ($dbPath === ':memory:' || $dbPath === '') {
    fwrite(STDERR, "Reset is not supported for in-memory SQLite databases.\n");
    exit(1);
}

if (file_exists($dbPath) && !unlink($dbPath)) {
    fwrite(STDERR, "Unable to delete database file: {$dbPath}\n");
    exit(1);
}

echo "Database reset completed.\n";

require __DIR__ . '/migrate.php';