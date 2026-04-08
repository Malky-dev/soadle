<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap/app.php';

use App\Http\Router;

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

if (!is_string($uri) || $uri === '') {
    $uri = '/';
}

$response = Router::dispatch($method, $uri);

http_response_code($response['status'] ?? 200);

foreach (($response['headers'] ?? []) as $name => $value) {
    header(sprintf('%s: %s', $name, $value));
}

echo $response['body'] ?? '';
