<?php
function loadEnv(string $file): void {
    if (!file_exists($file)) return;
    foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if (!$line || $line[0] === '#' || !str_contains($line, '=')) continue;
        [$k, $v] = explode('=', $line, 2);
        $k = trim($k); $v = trim($v, " \t\"'");
        putenv("$k=$v");
        $_ENV[$k] = $_SERVER[$k] = $v;
    }
}
loadEnv(dirname(__DIR__) . '/.env');

function env(string $key, mixed $default = null): mixed {
    $v = getenv($key);
    return $v === false ? $default : $v;
}
