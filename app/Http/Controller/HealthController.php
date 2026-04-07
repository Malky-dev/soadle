<?php

declare(strict_types=1);

namespace App\Http\Controller;

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

        return [
            'status' => $status,
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode([
                'status' => $status === 200 ? 'ok' : 'error',
                'checks' => $checks,
                'timestamp' => time(),
            ]),
        ];
    }

    private function checkDatabase(): string
    {
        try {
            $pdo = Connection::make();

            // Minimal query to validate connection
            $pdo->query('SELECT 1');

            return 'ok';
        } catch (Throwable $e) {
            return 'error';
        }
    }
}