<?php
// ── XSS ──────────────────────────────────────────────────────
function e(mixed $v): string {
    return htmlspecialchars((string)$v, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8');
}

// ── CSRF ─────────────────────────────────────────────────────
function csrf_token(): string {
    if (empty($_SESSION['_csrf'])) $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['_csrf'];
}
function csrf_field(): string {
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}
function csrf_verify(): void {
    $t = $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (!hash_equals(csrf_token(), $t)) {
        http_response_code(403); die('CSRF check failed.');
    }
}

// ── Auth ──────────────────────────────────────────────────────
function user(): ?array        { return $_SESSION['auth'] ?? null; }
function logged_in(): bool     { return !empty($_SESSION['auth']); }
function user_role(): string   { return $_SESSION['auth']['role'] ?? ''; }

function require_login(): void {
    if (!logged_in()) redirect(APP_URL . '/login');
}
function require_role(string ...$roles): void {
    require_login();
    if (!in_array(user_role(), $roles, true)) {
        http_response_code(403); die('Access denied.');
    }
}

// ── Redirect ─────────────────────────────────────────────────
function redirect(string $url): never {
    header('Location: ' . $url); exit;
}

// ── Flash ─────────────────────────────────────────────────────
function flash(string $type, string $msg): void {
    $_SESSION['_flash'][] = compact('type', 'msg');
}
function get_flash(): array {
    $f = $_SESSION['_flash'] ?? []; unset($_SESSION['_flash']); return $f;
}

// ── View ──────────────────────────────────────────────────────
function view(string $tpl, array $data = []): void {
    extract($data, EXTR_SKIP);
    $file = VIEWS . '/' . str_replace('.', '/', $tpl) . '.php';
    if (!file_exists($file)) throw new \RuntimeException("View not found: $tpl");
    require $file;
}

function render(string $tpl, array $data = []): void {
    // Buffer the page content then wrap in layout
    extract($data, EXTR_SKIP);
    $file = VIEWS . '/' . str_replace('.', '/', $tpl) . '.php';
    if (!file_exists($file)) throw new \RuntimeException("View not found: $tpl");
    ob_start();
    require $file;
    $pageContent = ob_get_clean();
    require VIEWS . '/partials/layout.php';
}

// ── JSON ──────────────────────────────────────────────────────
function json_out(mixed $data, int $code = 200): never {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// ── Sanitize ──────────────────────────────────────────────────
function clean(string $v): string { return trim(strip_tags($v)); }

// ── Slug ──────────────────────────────────────────────────────
function make_slug(string $t): string {
    $t = mb_strtolower($t, 'UTF-8');
    $cyr = ['а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я'];
    $lat = ['a','b','v','g','d','e','yo','j','z','i','y','k','l','m','n','o','p','r','s','t','u','f','x','ts','ch','sh','sh','','i','','e','yu','ya'];
    $t = str_replace($cyr, $lat, $t);
    $t = preg_replace('/[^a-z0-9\s\-]/', '', $t);
    $t = preg_replace('/[\s\-]+/', '-', trim($t));
    return $t ?: 'service-' . time();
}

// ── Upload image ──────────────────────────────────────────────
function upload_img(array $file, string $dir): string|false {
    if ($file['error'] !== UPLOAD_ERR_OK) return false;
    $max = (int)env('UPLOAD_MAX_MB', 5) * 1024 * 1024;
    if ($file['size'] > $max) return false;
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg','jpeg','png','webp'], true)) return false;
    $fi = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($fi, $file['tmp_name']);
    finfo_close($fi);
    if (!in_array($mime, ['image/jpeg','image/png','image/webp'], true)) return false;
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    $name = bin2hex(random_bytes(12)) . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], "$dir/$name")) return false;
    return $name;
}

// ── Haversine ─────────────────────────────────────────────────
function haversine(float $lat1, float $lon1, float $lat2, float $lon2): float {
    $R = 6371;
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat/2)**2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2)**2;
    return $R * 2 * atan2(sqrt($a), sqrt(1 - $a));
}

// ── Asset / img URL ───────────────────────────────────────────
function asset(string $p): string  { return APP_URL . '/' . ltrim($p, '/'); }
function svc_img(string $f): string { return APP_URL . '/uploads/services/' . $f; }
function avt_img(string $f): string { return APP_URL . '/uploads/avatars/'  . $f; }

// ── Paginate ──────────────────────────────────────────────────
function paginate(int $total, int $per, int $page): array {
    $pages = max(1, (int)ceil($total / $per));
    $page  = max(1, min($page, $pages));
    return ['total'=>$total,'per'=>$per,'pages'=>$pages,'page'=>$page,'offset'=>($page-1)*$per];
}

// ── OTP ───────────────────────────────────────────────────────
function make_otp(): string {
    return str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
}

// ── Specialization list ───────────────────────────────────────
function specializations(): array {
    return ['general','engine','body','electrical','tires','ac',
            'transmission','brake','suspension','painting','washing',
            'diagnostics','oil_change'];
}

// ── Viloyatlar ────────────────────────────────────────────────
function viloyatlar(): array {
    return [
        'toshkent_sh'     => "Toshkent shahri",
        'toshkent'        => "Toshkent viloyati",
        'andijon'         => "Andijon",
        'fargona'         => "Farg'ona",
        'namangan'        => "Namangan",
        'samarqand'       => "Samarqand",
        'buxoro'          => "Buxoro",
        'qashqadaryo'     => "Qashqadaryo",
        'surxondaryo'     => "Surxondaryo",
        'jizzax'          => "Jizzax",
        'sirdaryo'        => "Sirdaryo",
        'xorazm'          => "Xorazm",
        'navoiy'          => "Navoiy",
        'qoraqalpogiston' => "Qoraqalpog'iston",
    ];
}

// ── Work days map ─────────────────────────────────────────────
function work_days(): array {
    return ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
}

// ── Stars HTML ────────────────────────────────────────────────
function stars(float $avg, bool $input = false): string {
    if ($input) {
        $html = '<div class="star-input" id="starInput">';
        for ($i = 1; $i <= 5; $i++)
            $html .= "<span class=\"star\" data-v=\"$i\">★</span>";
        $html .= '</div><input type="hidden" name="rating" id="ratingVal">';
        return $html;
    }
    $full  = (int)round($avg);
    $html  = '<span class="stars">';
    for ($i = 1; $i <= 5; $i++)
        $html .= $i <= $full ? '★' : '<span style="opacity:.3">★</span>';
    $html .= '</span>';
    return $html;
}

// ── Number format ─────────────────────────────────────────────
function money(int $n): string {
    return number_format($n, 0, '', ' ');
}
