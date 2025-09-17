<?php
use App\Core\Session;
use App\Models\User;

if (!function_exists('csrf')) {
    function csrf(): string { return Session::csrfToken(); }
}
if (!function_exists('csrf_token')) {
    function csrf_token(): string { return Session::csrfToken(); }
}
if (!function_exists('session')) {
    function session(string $key, $default = null) { return $_SESSION[$key] ?? $default; }
}
