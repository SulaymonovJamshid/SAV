<?php
require_once __DIR__ . '/env.php';

// ── Error reporting ───────────────────────────────────────────
if (env('APP_DEBUG','false') === 'true') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}
ini_set('log_errors',  '1');
ini_set('error_log',   dirname(__DIR__) . '/logs/app.log');

// ── Paths & URL ───────────────────────────────────────────────
define('ROOT',      dirname(__DIR__));
define('APP',       ROOT . '/app');
define('VIEWS',     APP  . '/Views');
define('PUB',       ROOT . '/public');
define('UPLOAD_SVC', PUB . '/uploads/services');
define('UPLOAD_AVT', PUB . '/uploads/avatars');
define('APP_URL',   rtrim(env('APP_URL','http://localhost/sav/public'), '/'));

// ── Session ───────────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_samesite', 'Lax');
    ini_set('session.gc_maxlifetime', (int) env('SESSION_LIFETIME', 7200));
    session_start();
}

// ── Autoloader ────────────────────────────────────────────────
spl_autoload_register(function (string $class): void {
    $map = ['App\\Core\\'        => APP . '/Core/',
            'App\\Controllers\\' => APP . '/Controllers/',
            'App\\Models\\'      => APP . '/Models/'];
    foreach ($map as $ns => $dir) {
        if (str_starts_with($class, $ns)) {
            $f = $dir . str_replace('\\','/',substr($class,strlen($ns))) . '.php';
            if (file_exists($f)) { require_once $f; return; }
        }
    }
});

// ── Helpers ───────────────────────────────────────────────────
require_once APP . '/Core/helpers.php';
require_once APP . '/Core/lang.php';

// ── DB ────────────────────────────────────────────────────────
require_once APP . '/Core/DB.php';
use App\Core\DB;
