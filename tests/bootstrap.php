<?php

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    $basePath = BASE_PATH . '/src/';

    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = $basePath . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use App\Domain\Character;

if (!function_exists('buildCharacter')) {
    function buildCharacter(
        int $id,
        string $name,
        string $gender = 'male',
        int $firstAppearanceSeason = 1,
        ?int $deathSeason = null,
        string $affiliation = 'SAMCRO',
        ?string $imagePath = null
    ): Character {
        return new Character(
            id: $id,
            name: $name,
            gender: $gender,
            firstAppearanceSeason: $firstAppearanceSeason,
            deathSeason: $deathSeason,
            affiliation: $affiliation,
            imagePath: $imagePath
        );
    }
}

if (!function_exists('assertSame')) {
    function assertSame(mixed $expected, mixed $actual): void
    {
        if ($expected !== $actual) {
            throw new RuntimeException(sprintf(
                'Expected %s, got %s.',
                exportValue($expected),
                exportValue($actual)
            ));
        }
    }
}

if (!function_exists('assertCount')) {
    function assertCount(int $expectedCount, array $items): void
    {
        $actualCount = count($items);

        if ($expectedCount !== $actualCount) {
            throw new RuntimeException(sprintf(
                'Expected count %d, got %d.',
                $expectedCount,
                $actualCount
            ));
        }
    }
}

if (!function_exists('assertTrue')) {
    function assertTrue(bool $condition, string $message = 'Condition is false.'): void
    {
        if ($condition !== true) {
            throw new RuntimeException($message);
        }
    }
}

if (!function_exists('assertFalse')) {
    function assertFalse(bool $condition, string $message = 'Condition is true.'): void
    {
        if ($condition !== false) {
            throw new RuntimeException($message);
        }
    }
}

if (!function_exists('exportValue')) {
    function exportValue(mixed $value): string
    {
        return var_export($value, true);
    }
}