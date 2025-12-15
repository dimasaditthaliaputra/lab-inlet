<?php

$guestRoutes = require __DIR__ . '/guest.php';
$adminRoutes = require __DIR__ . '/admin.php';
$mahasiswaRoutes = require __DIR__ . '/mahasiswa.php';

$routes = $guestRoutes;

foreach ($adminRoutes as $method => $paths) {
    if (!isset($routes[$method])) {
        $routes[$method] = [];
    }
    $routes[$method] = array_merge($routes[$method], $paths);
}

foreach ($mahasiswaRoutes as $method => $paths) {
    if (!isset($routes[$method])) {
        $routes[$method] = [];
    }
    $routes[$method] = array_merge($routes[$method], $paths);
}

return $routes;