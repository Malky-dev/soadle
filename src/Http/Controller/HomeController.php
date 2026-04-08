<?php

declare(strict_types=1);

namespace App\Http\Controller;

final class HomeController extends AbstractController
{
    public function index(): array
    {
        return $this->render('home');
    }
}