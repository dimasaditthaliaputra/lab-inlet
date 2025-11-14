<?php

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\RolesController;
use App\Controllers\UserController;

return prefix('admin', route_group([

    // Login
    get('/login', [AuthController::class, 'showLoginForm']),
    post('/login/process', [AuthController::class, 'login']),
    post('/logout', [AuthController::class, 'logout']),

    // Dashboard
    get('/dashboard', [DashboardController::class, 'index']),

    // Roles
    get('/roles', [RolesController::class, 'index']),
    get('/roles/data', [RolesController::class, 'data']),
    post('/roles', [RolesController::class, 'store']),
    get('/roles/{id}/edit', [RolesController::class, 'edit']),
    put('/roles/{id}', [RolesController::class, 'update']),
    delete('/roles/{id}', [RolesController::class, 'destroy']),

    get('/user', [UserController::class, 'index']),
    get('/user/data', [UserController::class, 'data']),
    post('/user', [UserController::class, 'store']),
    get('/user/{id}/edit', [UserController::class, 'edit']),
    put('/user/{id}', [UserController::class, 'update']),
    delete('/user/{id}', [UserController::class, 'destroy']),
]));
