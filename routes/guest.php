<?php

use App\Controllers\HomeController;

return route_group([
    get('/', [HomeController::class, 'index']),
]);