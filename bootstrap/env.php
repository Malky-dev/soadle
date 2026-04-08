<?php

declare(strict_types=1);

$path = dirname(__DIR__) . '/.env';

if (!is_readable($path)) {
    return;
}

$raw = file($path, FILE_IGNORE_NEW_LINES);

if ($raw === false) {
    return;
}

foreach ($raw as $line) {
    $line = trim($line);

    if ($line === '' || str_starts_with($line, '#')) {
        continue;
    }

    if (!str_contains($line, '=')) {
        continue;
    }

    [$name, $value] = explode('=', $line, 2);
    $name = trim($name);

    if ($name === '') {
        continue;
    }

    $value = trim($value);
    $value = trim($value, '"\'');

    $_ENV[$name] = $value;
    putenv($name . '=' . $value);
}
