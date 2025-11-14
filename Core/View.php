<?php

namespace Core;

class View
{
    private static function getSafeViewPath($view)
    {
        $viewPath = str_replace(['.', '/'], DIRECTORY_SEPARATOR, $view);

        $basePath = realpath(__DIR__ . '/../app/Views/');

        if (!$basePath) {
            die('Directory not found');
        }

        $viewFile = realpath($basePath . DIRECTORY_SEPARATOR . $viewPath . '.php');

        if (!$viewFile || strpos($viewFile, $basePath) !== 0) {
            return false;
        }

        return $viewFile;
    }

    /**
     * Render view file
     */
    public static function render($view, $data = [])
    {
        $viewFile = self::getSafeViewPath($view);

        if ($viewFile && file_exists($viewFile)) {
            extract($data);
            require_once $viewFile;
        } else {
            die("View file not found: " . htmlspecialchars($view, ENT_QUOTES, 'UTF-8'));
        }
    }

    /**
     * Render view with layout
     */
    public static function renderWithLayout($view, $data = [], $layout = 'layouts/app')
    {
        $viewFile = self::getSafeViewPath($view);
        $layoutFile = self::getSafeViewPath($layout);

        if (!$viewFile) {
            die("View file not found: " . htmlspecialchars($view, ENT_QUOTES, 'UTF-8'));
        }

        if (!$layoutFile) {
            die("Layout file not found: " . htmlspecialchars($layout, ENT_QUOTES, 'UTF-8'));
        }

        extract($data);

        ob_start();
        require_once $viewFile;
        $content = ob_get_clean();

        require_once $layoutFile;
    }
}
