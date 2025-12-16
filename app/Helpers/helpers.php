<?php

use App\Models\LogActivity;

/**
 * Helper Functions untuk MVC Framework
 */

if (!function_exists('base_url')) {
    /**
     * Generate base URL
     */
    if (!function_exists('base_url')) {
        function base_url($path = '')
        {
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
                || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";


            $host = rtrim($_SERVER['HTTP_HOST'], '/');


            $scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));


            if (strlen($scriptName) > 1) {
                $scriptName = rtrim($scriptName, '/');
            }

            $baseUrl = $protocol . $host . $scriptName;

            return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
        }
    }
}

if (!function_exists('asset')) {
    function asset($path)
    {
        return rtrim(base_url(), '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('current_url')) {
    function current_url($relative = false)
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
            || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

        $host = rtrim($_SERVER['HTTP_HOST'], '/');
        $scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));

        if (strlen($scriptName) > 1) {
            $scriptName = rtrim($scriptName, '/');
        }

        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if ($relative) {
            return $requestUri;
        }

        $baseUrl = $protocol . $host . $scriptName;
        return rtrim($baseUrl, '/') . rtrim($requestUri, '/');
    }
}

if (!function_exists('current_route')) {
    function current_route()
    {
        return current_url(true);
    }
}

if (!function_exists('is_route')) {
    function is_route($route)
    {
        $currentRoute = current_route();
        $route = '/' . ltrim($route, '/');

        return $currentRoute === $route;
    }
}

if (!function_exists('is_route_prefix')) {
    function is_route_prefix($prefix)
    {
        $currentRoute = current_route();
        $prefix = '/' . ltrim($prefix, '/');
        $prefix = rtrim($prefix, '/');

        return strpos($currentRoute, $prefix) === 0;
    }
}

if (!function_exists('redirect')) {
    function redirect($url)
    {
        $location = $url;

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            $location = base_url($url);
        }

        header('Location: ' . $location);
        exit;
    }
}

if (!function_exists('old')) {
    function old($key, $default = '')
    {
        return $_SESSION['old'][$key] ?? $default;
    }
}

if (!function_exists('flash')) {
    function flash($key, $message = null)
    {
        if ($message === null) {
            $value = $_SESSION['flash'][$key] ?? null;
            unset($_SESSION['flash'][$key]);
            return $value;
        }
        $_SESSION['flash'][$key] = $message;
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field()
    {
        return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
    }
}

if (!function_exists('dd')) {
    function dd(...$vars)
    {
        foreach ($vars as $var) {
            echo '<pre>';
            var_dump($var);
            echo '</pre>';
        }
        die();
    }
}

if (!function_exists('dump')) {
    function dump(...$vars)
    {
        foreach ($vars as $var) {
            echo '<pre>';
            var_dump($var);
            echo '</pre>';
        }
    }
}

if (!function_exists('json_response')) {
    function json_response($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}

if (!function_exists('success')) {
    function success($message, $data = [], $statusCode = 200)
    {
        json_response([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }
}

if (!function_exists('error')) {
    function error($message, $errors = [], $statusCode = 400)
    {
        json_response([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $statusCode);
    }
}

if (!function_exists('sanitize')) {
    function sanitize($string)
    {
        $string = $string ?? '';
        return htmlspecialchars(strip_tags((string)$string), ENT_QUOTES, 'UTF-8');
    }

    function e($string)
    {
        return sanitize($string);
    }
}

if (!function_exists('e')) {
    function e($string)
    {
        return sanitize($string);
    }
}

if (!function_exists('request')) {
    function request($key = null, $default = null)
    {
        static $all_data = null;

        if ($all_data === null) {
            $all_data = array_merge($_GET, $_POST);

            $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
            if (in_array($method, ['PUT', 'PATCH', 'DELETE'])) {

                $input = file_get_contents('php://input');

                $parsed_data = [];
                parse_str($input, $parsed_data);

                $all_data = array_merge($all_data, $parsed_data);
            }
        }

        if ($key === null) {
            return $all_data;
        }

        return $all_data[$key] ?? $default;
    }
}

if (!function_exists('method')) {
    function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}

if (!function_exists('is_post')) {
    function is_post()
    {
        return method() === 'POST';
    }
}

if (!function_exists('is_get')) {
    function is_get()
    {
        return method() === 'GET';
    }
}

if (!function_exists('is_ajax')) {
    function is_ajax()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
}

if (!function_exists('config')) {
    function config($key, $default = null)
    {
        $keys = explode('.', $key);
        $file = array_shift($keys);
        $configFile = __DIR__ . '/../../config/' . $file . '.php';

        if (!file_exists($configFile)) {
            return $default;
        }

        $config = require $configFile;

        foreach ($keys as $k) {
            if (!isset($config[$k])) {
                return $default;
            }
            $config = $config[$k];
        }

        return $config;
    }
}

if (!function_exists('session')) {
    function session($key = null, $value = null)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($key === null) return $_SESSION;

        if ($value === null)
            return $_SESSION[$key] ?? null;

        $_SESSION[$key] = $value;
    }
}

if (!function_exists('clear_session')) {
    function clear_session()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_unset();

        session_destroy();
    }
}

if (!function_exists('cookie')) {
    function cookie($key = null, $value = null, $exp_sec = 3600, $path = '/')
    {
        if ($key === null) {
            return $_COOKIE;
        }

        if ($value === null) {
            return $_COOKIE[$key] ?? null;
        }


        $exp_time = time() + $exp_sec;

        setcookie($key, $value, $exp_time, $path);
    }
}

if (!function_exists('clear_cookie')) {
    function clear_cookie($key, $path = '/')
    {
        unset($_COOKIE[$key]);

        setcookie($key, '', time() - 3600, $path);
    }
}

if (!function_exists('auth')) {
    function auth()
    {
        return session('user');
    }
}

if (!function_exists('is_logged_in')) {

    function is_logged_in()
    {
        return session('user') !== null;
    }
}

if (!function_exists('attempt_auto_login')) {
    function attempt_auto_login()
    {
        if (is_logged_in()) {
            return true;
        }

        $token_cookie = cookie('remember_me_token');
        if (!$token_cookie) {
            return false;
        }

        list($user_id, $token) = explode(':', $token_cookie, 2);

        if (!$user_id || !$token) {
            clear_cookie('remember_me_token');
            return false;
        }

        try {
            $userModel = new \App\Models\User();
            $user = $userModel->findByRememberToken($user_id, $token);

            if ($user) {
                session('user', $user);

                $new_token = bin2hex(random_bytes(32));
                $expires = time() + (30 * 24 * 60 * 60);
                $userModel->updateRememberToken($user->id, hash('sha256', $new_token), date('Y-m-d H:i:s', $expires));
                cookie('remember_me_token', $user->id . ':' . $new_token, 30 * 24 * 60 * 60);

                return true;
            } else {
                // Token tidak valid atau kedaluwarsa
                clear_cookie('remember_me_token');
                return false;
            }
        } catch (\Exception $e) {
            clear_cookie('remember_me_token');
            return false;
        }
    }
}

if (!function_exists('view')) {
    /**
     * Render view
     */
    function view($view, $data = [])
    {
        return \Core\View::render($view, $data);
    }
}

if (!function_exists('view_with_layout')) {
    /**
     * Render view with layout
     */
    function view_with_layout($view, $data = [], $layout = 'admin/layouts/app')
    {
        return \Core\View::renderWithLayout($view, $data, $layout);
    }
}

if (!function_exists('view_with_layout_homepage')) {
    /**
     * Render view with layout
     */
    function view_with_layout_homepage($view, $data = [], $layout = 'home/layouts/app')
    {
        return \Core\View::renderWithLayout($view, $data, $layout);
    }
}

if (!function_exists('view_with_layout_mahasiswa')) {
    /**
     * Render view with layout
     */
    function view_with_layout_mahasiswa($view, $data = [], $layout = 'mahasiswa/layouts/app')
    {
        return \Core\View::renderWithLayout($view, $data, $layout);
    }
}

if (!function_exists('prefix')) {
    function prefix(string $prefix, array $routes): array
    {
        $prefixed = [];

        foreach ($routes as $method => $paths) {
            foreach ($paths as $path => $action) {
                $newPath = '/' . trim($prefix, '/') . $path;
                $prefixed[$method][$newPath] = $action;
            }
        }

        return $prefixed;
    }
}

if (!function_exists('env')) {
    function env($key, $default = null)
    {
        static $env = null;

        if ($env === null) {
            $path = __DIR__ . '/../../.env';
            if (!file_exists($path)) {
                return $default;
            }

            $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (str_starts_with(trim($line), '#')) continue;
                [$name, $value] = array_map('trim', explode('=', $line, 2));
                $value = trim($value, "\"'"); // hapus kutip
                $env[$name] = $value;
            }
        }

        return $env[$key] ?? $default;
    }

    if (!function_exists('response')) {
        /**
         * Helper function to create response instance
         */
        function response()
        {
            return new \Core\Response();
        }
    }

    if (!function_exists('get')) {
        /**
         * Define GET route
         */
        function get(string $path, $action): array
        {
            return ['GET' => [$path => $action]];
        }
    }

    if (!function_exists('post')) {
        /**
         * Define POST route
         */
        function post(string $path, $action): array
        {
            return ['POST' => [$path => $action]];
        }
    }

    if (!function_exists('put')) {
        /**
         * Define PUT route
         */
        function put(string $path, $action): array
        {
            return ['PUT' => [$path => $action]];
        }
    }

    if (!function_exists('delete')) {
        /**
         * Define DELETE route
         */
        function delete(string $path, $action): array
        {
            return ['DELETE' => [$path => $action]];
        }
    }

    if (!function_exists('route_match')) {
        /**
         * Define route for multiple methods
         */
        function route_match(array $methods, string $path, $action): array
        {
            $route = [];
            foreach ($methods as $method) {
                $route[strtoupper($method)][$path] = $action;
            }
            return $route;
        }
    }

    if (!function_exists('any')) {
        /**
         * Define route for all methods
         */
        function any(string $path, $action): array
        {
            return route_match(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], $path, $action);
        }
    }

    if (!function_exists('route_group')) {
        /**
         * Group routes with common attributes
         */
        function route_group(array $routes): array
        {
            $grouped = [];
            foreach ($routes as $route) {
                foreach ($route as $method => $paths) {
                    if (!isset($grouped[$method])) {
                        $grouped[$method] = [];
                    }
                    $grouped[$method] = array_merge($grouped[$method], $paths);
                }
            }
            return $grouped;
        }
    }

    if (!function_exists('method_field')) {
        /**
         * Generate hidden input for HTTP method spoofing
         */
        function method_field($method)
        {
            return '<input type="hidden" name="_method" value="' . strtoupper($method) . '">';
        }
    }

    if (!function_exists('validate_request')) {
        function validate_request($rules)
        {
            $validated = [];
            $errors = [];
            $requestData = request();

            foreach ($rules as $field => $options) {
                $isRequired = $options['required'] ?? false;
                $type = $options['type'] ?? 'string';
                $messages = $options['messages'] ?? [];
                $value = $requestData[$field] ?? null;

                $isUnique    = $options['unique'] ?? false;
                $table       = $options['table'] ?? null;
                $columnName  = $options['column'] ?? $field;

                if ($isRequired && (is_null($value) || (is_string($value) && trim($value) === ''))) {
                    $errors[$field] = $messages['required'] ?? ucfirst($field) . ' is required';
                    $validated[$field] = null;
                    continue;
                }

                if (!$isRequired && (is_null($value) || (is_string($value) && trim($value) === ''))) {
                    $validated[$field] = null;
                    continue;
                }

                $typeValid = match ($type) {
                    'string' => is_string($value),
                    'int', 'integer' => is_numeric($value),
                    'bool', 'boolean' => in_array($value, [true, false, '1', '0', 'true', 'false'], true),
                    'array' => is_array($value),
                    'email' => filter_var($value, FILTER_VALIDATE_EMAIL),
                    default => true
                };

                if (!$typeValid) {
                    $defaultMessage = ucfirst($field) . ' must be of type ' . $type;
                    $errors[$field] = $messages['type'] ?? $defaultMessage;
                    $validated[$field] = null;
                    continue;
                }

                $validated[$field] = match ($type) {
                    'int', 'integer' => (int)$value,
                    'bool', 'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
                    'email' => strtolower(trim($value)),
                    'string' => is_string($value) ? trim($value) : $value,
                    default => $value
                };

                if ($isUnique && $table) {
                    $db = new \Core\Database();

                    $query = "SELECT COUNT(*) AS count FROM {$table} WHERE {$columnName} = :value";

                    $result = $db->query($query)
                        ->bind(':value', $value)
                        ->fetch();

                    if ($result && $result->count > 0) {
                        $errors[$field] = $messages['unique'] ?? ucfirst($field) . ' sudah terdaftar.';
                        $validated[$field] = null;
                        continue;
                    }
                }

                $validated[$field] = match ($type) {
                    'int', 'integer' => (int)$value,
                    'bool', 'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
                    'email' => strtolower(trim($value)),
                    'string' => is_string($value) ? trim($value) : $value,
                    default => $value
                };
            }

            return [
                'success' => empty($errors),
                'data' => $validated,
                'errors' => $errors
            ];
        }
    }

    if (!function_exists('validate')) {
        function validate($rules)
        {
            return validate_request($rules);
        }
    }

    if (!function_exists('logActivity')) {
        function logActivity($action_type, $description = null, $table_name = null, $record_id = null, $old_data = null, $new_data = null)
        {
            $db = new \Core\Database();
            $user = session('user');

            $userId = $user ? $user->id : null;

            $db->query("
            INSERT INTO activity_log (
                id_user, action_type, table_name, record_id, description, old_data, new_data
            ) VALUES (
                :id_user, :action_type, :table_name, :record_id, :description, :old_data, :new_data
            )
        ");

            $db->bind(":id_user", $userId);
            $db->bind(":action_type", $action_type);
            $db->bind(":table_name", $table_name);
            $db->bind(":record_id", $record_id);
            $db->bind(":description", $description);
            $db->bind(":old_data", $old_data ? json_encode($old_data) : null);
            $db->bind(":new_data", $new_data ? json_encode($new_data) : null);

            $db->execute();
        }
    }

    if (!function_exists('get_recent_notifications')) {
    function get_recent_notifications()
    {
        $logModel = new LogActivity();
        
        return $logModel->getRecent();
    }
}

if (!function_exists('format_phone_for_display')) {
    function format_phone_for_display($phone)
    {
        if (empty($phone)) {
            return '';
        }

        $numericPhone = preg_replace('/[^0-9]/', '', $phone);

        if (substr($numericPhone, 0, 2) === '62') {
            $basePhone = substr($numericPhone, 2);
        } elseif (substr($numericPhone, 0, 1) === '0') {
            $basePhone = substr($numericPhone, 1);
        } else {
            $basePhone = $numericPhone;
        }

        $formattedPhone = implode('-', str_split($basePhone, 4));

        return "(+62) " . $formattedPhone;
    }
}
}
