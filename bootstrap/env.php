<?php

declare(strict_types=1);

// Load .env file if present
$envFile = dirname(__DIR__) . '/.env';

if (!file_exists($envFile)) {
    return;
}

$lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($lines as $line) {
    // Ignore comments
    if (str_starts_with(trim($line), '#')) {
        continue;
    }

    // Ignore invalid lines
    if (!str_contains($line, '=')) {
        continue;
    }

    [$key, $value] = explode('=', $line, 2);

    $key = trim($key);
    $value = trim($value);

    // Remove optional quotes
    $value = trim($value, "\"'");

    // Do not override existing environment variables
    if (isset($_ENV[$key])) {
        continue;
    }

    $_ENV[$key] = $value;
}