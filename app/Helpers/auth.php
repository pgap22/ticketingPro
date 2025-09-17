<?php
use App\Core\Session;
use App\Models\User;

/**
 * Retorna el usuario autenticado o null si no hay sesión.
 */
function current_user(): ?User {
    $uid = Session::get('user_id');
    if (!$uid) return null;
    return User::with('role')->find($uid);
}

/**
 * Retorna el nombre del rol del usuario autenticado.
 */
function current_role(): ?string {
    return current_user()?->role?->name;
}

/**
 * Redirige a la URL previa (Referer) o a un fallback.
 */
function redirect_back(string $fallback = '/'): void {
    $to = $_SERVER['HTTP_REFERER'] ?? $fallback;
    \redirect($to);
}
