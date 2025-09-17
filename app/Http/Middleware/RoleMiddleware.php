<?php
declare(strict_types=1);
namespace App\Http\Middleware;
use App\Core\Session;
use App\Models\User;

class RoleMiddleware
{
    /** @var string[] */
    private array $roles;
    public function __construct(array $roles) { $this->roles = $roles; }

    public function handle(callable $next)
    {
        $uid = Session::get('user_id');
        if (!$uid) {
            Session::flash('error', 'Debes iniciar sesión.');
            \redirect('/login');
        }
        $user = User::with('role')->find($uid);
        $name = $user?->role?->name;
        if (!$name || !in_array($name, $this->roles, true)) {
            Session::flash('error', 'No tienes permisos para acceder a esa sección.');
            $ref = $_SERVER['HTTP_REFERER'] ?? '/';
            \redirect($ref ?: '/');
        }
        return $next();
    }
}
