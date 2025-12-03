<?php

use App\Controllers\APIController;
use App\Controllers\HomeController;

return route_group([
    get('/', [HomeController::class, 'index']),
    get('/api/schoolar', [APIController::class, 'index']),
]);