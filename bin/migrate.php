<?php

declare(strict_types=1);

use App\Core\DB;

require __DIR__ . '/../vendor/autoload.php';

$pdo = DB::get();
$dir = __DIR__ . '/../database/migrations';
$files = glob($dir . '/*.sql');
sort($files);
foreach ($files as $file) {
    echo 'Running ' . basename($file) . PHP_EOL;
    $sql = file_get_contents($file);
    $pdo->exec($sql);
}
