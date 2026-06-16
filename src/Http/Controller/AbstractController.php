<?php

declare(strict_types=1);

namespace App\Http\Controller;

abstract class AbstractController
{
    /**
     * @param array<string, mixed> $data
     * @return array{
     *     status: int,
     *     headers: array<string, string>,
     *     body: string
     * }
     */
    protected function render(string $view, array $data = []): array
    {
        $viewPath = BASE_PATH . '/resources/views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            return $this->text('View not found: ' . $view, 500);
        }

        require_once BASE_PATH . '/src/Http/View/helpers.php';

        extract($data, EXTR_SKIP);

        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        return [
            'status' => 200,
            'headers' => [
                'Content-Type' => 'text/html; charset=UTF-8',
            ],
            'body' => $content,
        ];
    }

    protected function text(string $content, int $status = 200): array
    {
        return [
            'status' => $status,
            'headers' => [
                'Content-Type' => 'text/plain; charset=UTF-8',
            ],
            'body' => $content,
        ];
    }

    protected function badRequest(): array
    {
        return $this->text('Bad Request', 400);
    }
}