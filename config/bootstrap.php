<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

if (!function_exists('env')) {
    function env(string $key): mixed {
        return $_ENV[$key] ?? null;
    }
}
