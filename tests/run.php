<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

$coverageEnabled = extension_loaded('xdebug');

if ($coverageEnabled) {
    xdebug_start_code_coverage(XDEBUG_CC_UNUSED | XDEBUG_CC_DEAD_CODE);
}

$testFiles = glob(__DIR__ . '/**/*Test.php') ?: [];

sort($testFiles);

$failures = 0;

foreach ($testFiles as $testFile) {
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

if ($coverageEnabled) {
    $coverage = xdebug_get_code_coverage();
    xdebug_stop_code_coverage();

    require __DIR__ . '/support/CoverageReporter.php';
    printCoverageReport($coverage);
}

if ($failures > 0) {
    exit(1);
}

fwrite(STDOUT, "All tests passed.\n");