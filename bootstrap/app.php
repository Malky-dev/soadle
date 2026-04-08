<?php

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    $basePath = BASE_PATH . '/src/';

    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }

    // Translate the App\* namespace into the matching file path under /src.
    $relativeClass = substr($class, strlen($prefix));
    $file = $basePath . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Load environment variables before bootstrapping config-dependent services.
require_once BASE_PATH . '/bootstrap/env.php';