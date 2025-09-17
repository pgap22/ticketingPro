<?php
declare(strict_types=1);
namespace App\Http\Middleware;
use App\Core\Session;

class CsrfMiddleware
{
    public function handle(callable $next)
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $token = $_POST['_token'] ?? '';
            if (!Session::verifyCsrf($token)) {
                Session::flash('error', 'Token CSRF inválido.');
                \redirect('/');
            }
        }
        return $next();
    }
}
