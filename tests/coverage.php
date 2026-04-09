<?php

declare(strict_types=1);

$files = [];

foreach ($coverage as $file => $lines) {
    if (!str_contains($file, '/src/')) {
        continue;
    }

    $executed = count(array_filter($lines, fn($v) => $v > 0));
    $total = count($lines);

    $files[$file] = [
        'executed' => $executed,
        'total' => $total,
        'percent' => $total > 0 ? round(($executed / $total) * 100, 2) : 0,
    ];
}

echo "\nCoverage report:\n";

foreach ($files as $file => $data) {
    echo sprintf(
        "%s: %d%% (%d/%d)\n",
        basename($file),
        $data['percent'],
        $data['executed'],
        $data['total']
    );
}