<?php
declare(strict_types=1);
use FastRoute\RouteCollector;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\CsrfMiddleware;

$routes = [];

function route(string $method, string $path, array $handler, array $middleware = []): void
{
    global $routes;
    $routes[] = compact('method','path','handler','middleware');
}
function parseMiddleware(array $middleware): array
{
    $stack = [];
    foreach ($middleware as $mw) {
        if ($mw === 'auth') {
            $stack[] = new AuthMiddleware();
        } elseif ($mw === 'csrf') {
            $stack[] = new CsrfMiddleware();
        } elseif (str_starts_with($mw, 'role:')) {
            $roles = explode(',', substr($mw, strlen('role:')));
            $roles = array_map('trim', $roles);
            $stack[] = new RoleMiddleware($roles);
        }
    }
    return $stack;
}
function dispatchRoutes(): void
{
    global $routes;
    $dispatcher = FastRoute\simpleDispatcher(function(RouteCollector $r) use ($routes) {
        foreach ($routes as $rt) {
            [$ctrl, $method] = $rt['handler'];
            $r->addRoute($rt['method'], $rt['path'], [
                'controller' => $ctrl,
                'method' => $method,
                'middleware' => $rt['middleware']
            ]);
        }
    });

    $httpMethod = $_SERVER['REQUEST_METHOD'];
    $uri = $_SERVER['REQUEST_URI'];
    if (false !== $pos = strpos($uri, '?')) {
        $uri = substr($uri, 0, $pos);
    }
    $uri = rawurldecode($uri);

    $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
    switch ($routeInfo[0]) {
        case FastRoute\Dispatcher::NOT_FOUND:
            http_response_code(404);
            echo '404 — Página no encontrada';
            break;
        case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            http_response_code(405);
            echo '405 — Método no permitido';
            break;
        case FastRoute\Dispatcher::FOUND:
            $handler = $routeInfo[1];
            $vars = $routeInfo[2];

            $middleware = parseMiddleware($handler['middleware']);
            $controller = new $handler['controller']();
            $method = $handler['method'];

            $runner = function() use ($controller, $method, $vars) {
                $ref = new ReflectionMethod($controller, $method);
                $params = [];
                foreach ($ref->getParameters() as $p) {
                    $name = $p->getName();
                    if (isset($vars[$name])) {
                        $params[] = is_numeric($vars[$name]) ? (int)$vars[$name] : $vars[$name];
                    } else {
                        $params[] = null;
                    }
                }
                $controller->$method(...$params);
            };

            $pipeline = array_reduce(
                array_reverse($middleware),
                fn($next, $mw) => fn() => $mw->handle($next),
                $runner
            );
            $pipeline();
            break;
    }
}
