<?php

namespace Core;

class App
{
    protected $controller = 'App\Controllers\ErrorController'; // Default controller
    protected $method = 'notFound';
    protected $params = [];
    protected $routes = [];

    public function __construct()
    {
        $this->routes = require __DIR__ . '/../routes/web.php';

        $url = $this->parseUrl();

        $requestMethod = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

        $path = '/' . implode('/', $url);
        if (empty($url)) {
            $path = '/';
        }

        $matched = false;

        foreach ($this->routes[$requestMethod] ?? [] as $routePath => $action) {
            $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([^/]+)', $routePath);

            if (preg_match('#^' . $pattern . '$#', $path, $matches)) {
                array_shift($matches); // hapus full match
                [$this->controller, $this->method] = $action;
                $this->params = $matches;
                $matched = true;
                break;
            }
        }

        if (!$matched) {
            $this->controller = 'App\Controllers\ErrorController';
            $this->method = 'notFound';
        }

        $controllerFile = __DIR__ . '/../App/Controllers/' . str_replace('App\\Controllers\\', '', $this->controller) . '.php';

        if (!file_exists($controllerFile)) {
            $this->handleManualNotFound("Controller file not found: $controllerFile");
        }

        $this->controller = new $this->controller;

        if (!method_exists($this->controller, $this->method)) {
            $this->handleManualNotFound("Method not found: {$this->method}");
        }
    }

    public function run()
    {
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    protected function parseUrl()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));

        if ($scriptName === '/' || $scriptName === '.') {
            $scriptName = '';
        }

        if (strpos($uri, $scriptName) === 0) {
            $uri = substr($uri, strlen($scriptName));
        }

        $uri = trim($uri, '/');
        return $uri === '' ? [] : explode('/', $uri);
    }

    /**
     * Fallback 404 jika ErrorController atau method-nya tidak ditemukan
     */
    protected function handleManualNotFound($message = "Halaman tidak ditemukan.")
    {
        http_response_code(404);
        echo "<h1>404 Not Found</h1>";
        echo "<p>$message</p>";
        die();
    }
}
