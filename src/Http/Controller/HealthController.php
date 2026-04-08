<?php

declare(strict_types=1);

namespace App\Http\Controller;

use App\Http\Response\JsonResponse;
use App\Infrastructure\Database\Connection;
use Throwable;

final class HealthController
{
    public function handle(): array
    {
        $checks = [
            'app' => 'ok',
            'db' => $this->checkDatabase(),
        ];

        $status = in_array('error', $checks, true) ? 500 : 200;

        return JsonResponse::make([
            'status' => $status === 200 ? 'ok' : 'error',
            'checks' => $checks,
            'timestamp' => time(),
        ], $status);
    }

    private function checkDatabase(): string
    {
        try {
            // Reuse the shared PDO entrypoint exposed by the infrastructure layer.
            $pdo = Connection::get();
            $pdo->query('SELECT 1');

            return 'ok';
        } catch (Throwable) {
            return 'error';
        }
    }
}