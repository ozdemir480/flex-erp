<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Bootstrap;

$app = new Bootstrap();
$app->run();
