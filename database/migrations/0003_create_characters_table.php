<?php

declare(strict_types=1);

return static function (\PDO $pdo): void {
    $driver = $pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);

    if ($driver === 'sqlite') {
        $pdo->exec(
            <<<SQL
            CREATE TABLE IF NOT EXISTS characters (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                gender TEXT NOT NULL,
                first_appearance_season INTEGER NOT NULL,
                death_season INTEGER NULL,
                affiliation TEXT NOT NULL,
                created_at TEXT NOT NULL
            )
            SQL
        );

        return;
    }

    $pdo->exec(
        <<<SQL
        CREATE TABLE IF NOT EXISTS characters (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            gender VARCHAR(20) NOT NULL,
            first_appearance_season TINYINT UNSIGNED NOT NULL,
            death_season TINYINT UNSIGNED NULL,
            affiliation VARCHAR(50) NOT NULL,
            created_at DATETIME NOT NULL
        )
        SQL
    );
};