<?php

declare(strict_types=1);

require_once __DIR__ . '/../bootstrap/app.php';

use App\Http\Router;

// Capture request
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Dispatch
$response = Router::dispatch($method, $uri);

// Emit response
http_response_code($response['status']);

foreach ($response['headers'] as $name => $value) {
    header("$name: $value");
}

echo $response['body'];