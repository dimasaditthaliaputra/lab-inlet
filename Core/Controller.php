<?php

namespace Core;

class Controller
{
    /**
     * Load view file
     */
    public function view($view, $data = [])
    {
        return View::render($view, $data);
    }

    /**
     * Load model
     */
    public function model($model)
    {
        $modelClass = 'App\\Models\\' . $model;
        return new $modelClass;
    }

    /**
     * Redirect to another page
     */
    public function redirect($url)
    {
        header('Location: ' . base_url($url));
        exit;
    }

    /**
     * Generate CSRF Token
     */
    protected function generateCsrfToken()
    {
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        return $token;
    }

    /**
     * Get CSRF Token
     */
    protected function getCsrfToken()
    {
        return $_SESSION['csrf_token'] ?? '';
    }

    /**
     * Set CSRF Token
     */
    protected function setCsrfToken($token)
    {
        $_SESSION['csrf_token'] = $token;
    }

    /**
     * Validate CSRF Token
     */
    protected function validateCsrf()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
                die('CSRF token validation failed');
            }
        }
    }
}