<?php

declare(strict_types=1);

return static function (\PDO $pdo): void {
    $columnExists = static function (\PDO $pdo, string $table, string $column): bool {
        $driver = $pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);

        if ($driver === 'sqlite') {
            $statement = $pdo->query("PRAGMA table_info('{$table}')");
            $columns = $statement->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($columns as $currentColumn) {
                if (($currentColumn['name'] ?? null) === $column) {
                    return true;
                }
            }

            return false;
        }

        $statement = $pdo->prepare(
            <<<SQL
            SELECT COUNT(*)
            FROM information_schema.columns
            WHERE table_schema = DATABASE()
              AND table_name = :table_name
              AND column_name = :column_name
            SQL
        );

        $statement->execute([
            'table_name' => $table,
            'column_name' => $column,
        ]);

        return (int) $statement->fetchColumn() > 0;
    };

    if ($columnExists($pdo, 'characters', 'image_path')) {
        return;
    }

    $driver = $pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);

    if ($driver === 'sqlite') {
        $pdo->exec('ALTER TABLE characters ADD COLUMN image_path TEXT NULL');
        return;
    }

    $pdo->exec('ALTER TABLE characters ADD COLUMN image_path VARCHAR(255) NULL');
};