<?php
declare(strict_types=1);

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Whoops\Run as WhoopsRun;
use Whoops\Handler\PrettyPageHandler;
use App\Core\Session;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory as ValidatorFactory;

define('BASE_PATH', realpath(__DIR__ . '/../../'));
chdir(BASE_PATH);

// Cargar env
$dotenv = Dotenv::createImmutable(BASE_PATH);
if (file_exists(BASE_PATH . '/.env')) {
    $dotenv->safeLoad();
}

date_default_timezone_set($_ENV['APP_TIMEZONE'] ?? 'UTC');

// Sesión
Session::start($_ENV['SESSION_NAME'] ?? 'TICKETINGPROSESSID');

$env = $_ENV['APP_ENV'] ?? 'production';
$debug = filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN);

// Whoops en dev
if ($env === 'local' && $debug) {
    $whoops = new WhoopsRun();
    $whoops->pushHandler(new PrettyPageHandler());
    $whoops->register();
}

// Eloquent
$capsule = new Capsule();
$capsule->addConnection([
    'driver'    => $_ENV['DB_DRIVER'] ?? 'mysql',
    'host'      => $_ENV['DB_HOST'] ?? '127.0.0.1',
    'port'      => $_ENV['DB_PORT'] ?? 3306,
    'database'  => $_ENV['DB_DATABASE'] ?? 'ticketingpro_v2',
    'username'  => $_ENV['DB_USERNAME'] ?? 'root',
    'password'  => $_ENV['DB_PASSWORD'] ?? '',
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
]);
$capsule->setEventDispatcher(new Dispatcher(new Container()));
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Blade
$viewConfig = require BASE_PATH . '/config/view.php';
$viewPaths = $viewConfig['paths'];
$cachePath = $viewConfig['cache'];

$bladeCompiler = new Illuminate\View\Compilers\BladeCompiler(
    new Illuminate\Filesystem\Filesystem(),
    $cachePath
);
$engineResolver = new Illuminate\View\Engines\EngineResolver();
$engineResolver->register('blade', function() use ($bladeCompiler) {
    return new Illuminate\View\Engines\CompilerEngine($bladeCompiler);
});
$engineResolver->register('php', function() {
    return new Illuminate\View\Engines\PhpEngine();
});
$viewFinder = new Illuminate\View\FileViewFinder(new Illuminate\Filesystem\Filesystem(), $viewPaths);
$viewFactory = new Illuminate\View\Factory($engineResolver, $viewFinder, new Dispatcher(new Container()));

$app = [
    'view' => $viewFactory,
    'validator' => (function() {
        $loader = new ArrayLoader();
        $translator = new Translator($loader, 'es');
        return new ValidatorFactory($translator);
    })()
];

// Helpers globales
if (!function_exists('view')) {
    function view(string $template, array $data = []) : string {
        global $app;
        return $app['view']->make($template, $data)->render();
    }
}
if (!function_exists('redirect')) {
    function redirect(string $to, int $code = 302): void {
        header('Location: ' . $to, true, $code);
        exit;
    }
}

// Registrar helpers específicos
require_once BASE_PATH . '/resources/views/helpers.php';
require_once BASE_PATH . '/app/Helpers/vite.php';

// Logging en producción
if (!($env === 'local' && $debug)) {
    set_exception_handler(function(Throwable $e) {
        $logDir = BASE_PATH . '/storage/logs';
        if (!is_dir($logDir)) { @mkdir($logDir, 0777, true); }
        $msg = '['.date('c').'] EXCEPTION: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine() . "\n" . $e->getTraceAsString() . "\n";
        error_log($msg, 3, $logDir . '/app.log');
        http_response_code(500);
        echo view('errors.500');
        exit;
    });
    set_error_handler(function($severity, $message, $file, $line) {
        $logDir = BASE_PATH . '/storage/logs';
        if (!is_dir($logDir)) { @mkdir($logDir, 0777, true); }
        $msg = '['.date('c').'] ERROR: ' . $message . ' in ' . $file . ':' . $line . "\n";
        error_log($msg, 3, $logDir . '/app.log');
        return false; // deja que PHP maneje si aplica
    });
    register_shutdown_function(function() {
        $error = error_get_last();
        if ($error) {
            $logDir = BASE_PATH . '/storage/logs';
            if (!is_dir($logDir)) { @mkdir($logDir, 0777, true); }
            $msg = '['.date('c').'] SHUTDOWN: ' . json_encode($error) . "\n";
            error_log($msg, 3, $logDir . '/app.log');
        }
    });
}
