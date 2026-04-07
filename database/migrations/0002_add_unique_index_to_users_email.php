<?php

declare(strict_types=1);

return static function (\PDO $pdo): void {
    $indexExists = static function (\PDO $pdo, string $table, string $indexName): bool {
        $driver = $pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);

        if ($driver === 'sqlite') {
            $statement = $pdo->query("PRAGMA index_list('{$table}')");
            $indexes = $statement->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($indexes as $index) {
                if (($index['name'] ?? null) === $indexName) {
                    return true;
                }
            }

            return false;
        }

        $statement = $pdo->prepare(
            <<<SQL
            SELECT COUNT(*)
            FROM information_schema.statistics
            WHERE table_schema = DATABASE()
              AND table_name = :table_name
              AND index_name = :index_name
            SQL
        );

        $statement->execute([
            'table_name' => $table,
            'index_name' => $indexName,
        ]);

        return (int) $statement->fetchColumn() > 0;
    };

    if ($indexExists($pdo, 'users', 'uniq_users_email')) {
        return;
    }

    $pdo->exec('CREATE UNIQUE INDEX uniq_users_email ON users (email)');
};