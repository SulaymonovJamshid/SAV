<?php
namespace App\Core;

class Router {
    private array $routes = [];

    public function get(string $p, string $h): void  { $this->add('GET',  $p, $h); }
    public function post(string $p, string $h): void { $this->add('POST', $p, $h); }

    private function add(string $m, string $p, string $h): void {
        $this->routes[] = ['method' => $m, 'pattern' => $p, 'handler' => $h];
    }

    public function dispatch(string $uri, string $method): void {
        $uri = '/' . trim($uri, '/');
        foreach ($this->routes as $r) {
            if ($r['method'] !== $method) continue;
            $pat = '#^' . preg_replace('#\{([a-z]+)\}#', '(?P<$1>[^/]+)', $r['pattern']) . '$#u';
            if (!preg_match($pat, $uri, $m)) continue;
            $params = array_filter($m, 'is_string', ARRAY_FILTER_USE_KEY);
            [$cls, $act] = explode('@', $r['handler']);
            $cls = "App\\Controllers\\$cls";
            (new $cls())->$act(...array_values($params));
            return;
        }
        http_response_code(404);
        view('partials/404');
    }
}
