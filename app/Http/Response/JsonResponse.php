<?php

declare(strict_types=1);

namespace App\Http\Response;

final class JsonResponse
{
    public static function make(array $data, int $status = 200): array
    {
        return [
            'status' => $status,
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode($data),
        ];
    }
}