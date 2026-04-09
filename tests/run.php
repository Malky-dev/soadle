<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

$tests = [
    __DIR__ . '/Application/PlayGameTest.php',
];

$failures = 0;

foreach ($tests as $testFile) {
    $result = require $testFile;

    if (!is_array($result)) {
        fwrite(STDERR, sprintf("Invalid test file result: %s\n", $testFile));
        $failures++;
        continue;
    }

    foreach ($result as $testName => $test) {
        try {
            $test();
            fwrite(STDOUT, sprintf("PASS %s\n", $testName));
        } catch (Throwable $exception) {
            fwrite(STDERR, sprintf("FAIL %s\n", $testName));
            fwrite(STDERR, sprintf("  %s\n", $exception->getMessage()));
            $failures++;
        }
    }
}

if ($failures > 0) {
    exit(1);
}

fwrite(STDOUT, "All tests passed.\n");