<?php

use App\Controllers\DashboardMahasiswaController;
use App\Controllers\LogPresenceController;
use App\Controllers\MahasiswaPermissionController;
use App\Controllers\PresenceController;
use App\Controllers\ProfileMahasiswaController;

return prefix('mahasiswa', route_group([
    // Dashboard
    get('/dashboard', [DashboardMahasiswaController::class, 'index']),
    get('/profile', [ProfileMahasiswaController::class, 'index']),
    put('/profile/update', [ProfileMahasiswaController::class, 'update']),

    get('/presence', [PresenceController::class, 'index']),
    post('/presence/process', [PresenceController::class, 'process']),

    get('/request-permission', [MahasiswaPermissionController::class, 'index']),
    get('/request-permission/data', [MahasiswaPermissionController::class, 'data']),
    post('/request-permission/store', [MahasiswaPermissionController::class, 'store']),

    get('/log/presence', [LogPresenceController::class, 'index']),
    get('/log/presence/data', [LogPresenceController::class, 'data']),
]));
