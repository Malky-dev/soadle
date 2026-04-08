<?php

declare(strict_types=1);

namespace App\Http\Response;

final class JsonResponse
{
    /**
     * @param array<string, mixed> $data
     * @return array{
     *     status: int,
     *     headers: array<string, string>,
     *     body: string
     * }
     */
    public static function make(array $data, int $status = 200): array
    {
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if ($json === false) {
            $status = 500;
            $json = json_encode([
                'status' => 'error',
                'message' => 'Unable to encode JSON response.',
            ]);

            if ($json === false) {
                $json = '{"status":"error","message":"Unable to encode JSON response."}';
            }
        }

        return [
            'status' => $status,
            'headers' => [
                'Content-Type' => 'application/json; charset=UTF-8',
            ],
            'body' => $json,
        ];
    }
}