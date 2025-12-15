<?php

use App\Controllers\AuthController;
use App\Controllers\DashboardController;

return prefix('mahasiswa', route_group([
    // Dashboard
    get('/dashboard', [DashboardController::class, 'index']),
]));
