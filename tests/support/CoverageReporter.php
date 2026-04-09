<?php

declare(strict_types=1);

/**
 * @param array<string, array<int, int>> $coverage
 */
function printCoverageReport(array $coverage): void
{
    $targets = [
        '/src/Application/',
        '/src/Domain/',
    ];

    $rows = [];

    foreach ($coverage as $file => $lines) {
        $isTarget = false;

        foreach ($targets as $target) {
            if (str_contains(str_replace('\\', '/', $file), $target)) {
                $isTarget = true;
                break;
            }
        }

        if (!$isTarget) {
            continue;
        }

        $executable = 0;
        $executed = 0;

        foreach ($lines as $status) {
            if ($status === -2) {
                $executable++;
                continue;
            }

            if ($status > 0) {
                $executable++;
                $executed++;
            }
        }

        if ($executable === 0) {
            continue;
        }

        $rows[] = [
            'file' => basename($file),
            'executed' => $executed,
            'executable' => $executable,
            'percent' => round(($executed / $executable) * 100, 2),
        ];
    }

    usort($rows, static fn (array $a, array $b): int => strcmp($a['file'], $b['file']));

    fwrite(STDOUT, PHP_EOL . "Coverage report" . PHP_EOL);

    $totalExecuted = 0;
    $totalExecutable = 0;

    foreach ($rows as $row) {
        fwrite(STDOUT, sprintf(
            "- %s: %.2f%% (%d/%d)\n",
            $row['file'],
            $row['percent'],
            $row['executed'],
            $row['executable']
        ));

        $totalExecuted += $row['executed'];
        $totalExecutable += $row['executable'];
    }

    if ($totalExecutable > 0) {
        fwrite(STDOUT, sprintf(
            "Total: %.2f%% (%d/%d)\n",
            round(($totalExecuted / $totalExecutable) * 100, 2),
            $totalExecuted,
            $totalExecutable
        ));
    }
}