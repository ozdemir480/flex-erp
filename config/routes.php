<?php

declare(strict_types=1);

use App\Controllers\HomeController;
use App\Interfaces\RouterInterface;

return static function (RouterInterface $router): void {
    $router->get('/', [HomeController::class, 'index']);
};
