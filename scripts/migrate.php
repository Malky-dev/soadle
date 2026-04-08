<?php

declare(strict_types=1);

use App\Infrastructure\Database\Connection;

require_once __DIR__ . '/../bootstrap/app.php';

$pdo = Connection::get();

$sql = file_get_contents(__DIR__ . '/../database/schema.sql');

if ($sql === false) {
    fwrite(STDERR, "Unable to read database/schema.sql.\n");
    exit(1);
}

// Execute the schema as a single migration entrypoint for local setup.
$pdo->exec($sql);

fwrite(STDOUT, "Database migrated successfully.\n");