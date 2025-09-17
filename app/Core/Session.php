<?php
declare(strict_types=1);

namespace App\Core;

class Session
{
    public static function start(string $name = 'TICKETINGPROSESSID'): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_name($name);
            session_start([
                'cookie_httponly' => filter_var($_ENV['SESSION_HTTP_ONLY'] ?? true, FILTER_VALIDATE_BOOLEAN),
                'cookie_secure' => filter_var($_ENV['SESSION_SECURE'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'cookie_samesite' => $_ENV['SESSION_SAME_SITE'] ?? 'Lax',
            ]);
        }
        if (!isset($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }
    }
    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }
    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }
    public static function flash(string $key, ?string $value = null): ?string
    {
        if ($value === null) {
            $val = $_SESSION['_flash'][$key] ?? null;
            unset($_SESSION['_flash'][$key]);
            return $val;
        }
        $_SESSION['_flash'][$key] = $value;
        return null;
    }
    public static function destroy(): void
    {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }
    public static function csrfToken(): string
    {
        return $_SESSION['_csrf'] ?? '';
    }
    public static function verifyCsrf(?string $token): bool
    {
        return hash_equals(self::csrfToken(), (string)$token);
    }
}
