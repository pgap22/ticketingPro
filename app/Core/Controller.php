<?php
declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function render(string $view, array $data = []): void
    {
        echo \view($view, $data);
    }
    protected function redirect(string $to, int $code = 302): void
    {
        \redirect($to, $code);
    }
    protected function userId(): ?int
    {
        return Session::get('user_id');
    }
}
