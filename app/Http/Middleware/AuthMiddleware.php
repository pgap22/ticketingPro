<?php
declare(strict_types=1);
namespace App\Http\Middleware;
use App\Core\Session;

class AuthMiddleware
{
    public function handle(callable $next)
    {
        if (!Session::get('user_id')) {
            Session::flash('error', 'Debes iniciar sesión.');
            \redirect('/login');
        }
        return $next();
    }
}
