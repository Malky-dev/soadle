<?php

declare(strict_types=1);

require_once __DIR__ . '/../bootstrap/app.php';

use App\Infrastructure\Database\Connection;

$pdo = Connection::make();

ensureMigrationsTable($pdo);

$migrationsDirectory = __DIR__ . '/../database/migrations';
$migrationFiles = glob($migrationsDirectory . '/*.php');

if ($migrationFiles === false) {
    fwrite(STDERR, "Unable to read migration files.\n");
    exit(1);
}

sort($migrationFiles);

$appliedVersions = fetchAppliedVersions($pdo);

foreach ($migrationFiles as $migrationFile) {
    $version = basename($migrationFile, '.php');

    if (in_array($version, $appliedVersions, true)) {
        echo "[SKIP] {$version}\n";
        continue;
    }

    $migration = require $migrationFile;

    if (!is_callable($migration)) {
        fwrite(STDERR, "Migration {$version} must return a callable.\n");
        exit(1);
    }

    echo "[RUN ] {$version}\n";

    try {
        $pdo->beginTransaction();

        $migration($pdo);

        recordMigration($pdo, $version);

        $pdo->commit();

        echo "[OK  ] {$version}\n";
    } catch (Throwable $exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        fwrite(STDERR, "[FAIL] {$version}: {$exception->getMessage()}\n");
        exit(1);
    }
}

echo "Migrations completed.\n";

function ensureMigrationsTable(PDO $pdo): void
{
    $pdo->exec(
        <<<SQL
        CREATE TABLE IF NOT EXISTS schema_migrations (
            version VARCHAR(255) PRIMARY KEY,
            executed_at DATETIME NOT NULL
        )
        SQL
    );
}

function fetchAppliedVersions(PDO $pdo): array
{
    $statement = $pdo->query('SELECT version FROM schema_migrations ORDER BY version ASC');
    $versions = $statement->fetchAll(PDO::FETCH_COLUMN);

    return is_array($versions) ? $versions : [];
}

function recordMigration(PDO $pdo, string $version): void
{
    $statement = $pdo->prepare(
        'INSERT INTO schema_migrations (version, executed_at) VALUES (:version, :executed_at)'
    );

    $statement->execute([
        'version' => $version,
        'executed_at' => date('Y-m-d H:i:s'),
    ]);
}