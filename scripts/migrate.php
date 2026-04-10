<?php

declare(strict_types=1);

use App\Infrastructure\Database\Connection;
use PDO;
use Throwable;

require_once __DIR__ . '/../bootstrap/app.php';

$pdo = Connection::get();

$migrationFiles = glob(__DIR__ . '/../database/migrations/*.php');

if ($migrationFiles === false || $migrationFiles === []) {
    fwrite(STDERR, "No migration files found in database/migrations.\n");
    exit(1);
}

sort($migrationFiles);

foreach ($migrationFiles as $migrationFile) {
    $migration = require $migrationFile;

    if (!is_callable($migration)) {
        fwrite(STDERR, sprintf(
            "Migration file is invalid: %s\n",
            basename($migrationFile)
        ));
        exit(1);
    }

    try {
        $migration($pdo);
        fwrite(STDOUT, sprintf(
            "Migrated: %s\n",
            basename($migrationFile)
        ));
    } catch (Throwable $exception) {
        fwrite(STDERR, sprintf(
            "Migration failed: %s\n%s\n",
            basename($migrationFile),
            $exception->getMessage()
        ));
        exit(1);
    }
}

fwrite(STDOUT, "Database migrated successfully.\n");