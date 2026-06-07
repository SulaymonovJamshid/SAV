<?php
function load_lang(string $lang): void {
    $file = ROOT . "/lang/$lang/app.php";
    $GLOBALS['_lang'] = file_exists($file) ? require $file : [];
    $_SESSION['lang'] = $lang;
}

function t(string $key, array $rep = []): string {
    $v = $GLOBALS['_lang'][$key] ?? $key;
    foreach ($rep as $k => $val) $v = str_replace(":$k", $val, $v);
    return $v;
}

function lang(): string { return $_SESSION['lang'] ?? 'uz'; }

// Boot language
$_boot_lang = $_GET['lang'] ?? $_SESSION['lang'] ?? 'uz';
if (!in_array($_boot_lang, ['uz','ru','en'])) $_boot_lang = 'uz';
load_lang($_boot_lang);

// Persist lang preference for logged-in user
if (isset($_GET['lang']) && logged_in()) {
    \App\Core\DB::run('UPDATE users SET lang=? WHERE id=?', [$_boot_lang, user()['id']]);
    $_SESSION['auth']['lang'] = $_boot_lang;
    $uri = strtok($_SERVER['REQUEST_URI'], '?');
    header("Location: $uri"); exit;
}
