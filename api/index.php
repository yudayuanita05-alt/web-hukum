<?php

// Konfigurasi direktori sementara /tmp untuk lingkungan Serverless Vercel
$tmpDirs = [
    '/tmp/storage/app/public',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
];

foreach ($tmpDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// Inisialisasi Database SQLite di /tmp agar writable di Vercel
$sqliteSource = __DIR__ . '/../database/database.sqlite';
$sqliteDest = '/tmp/database.sqlite';
if (!file_exists($sqliteDest) && file_exists($sqliteSource)) {
    @copy($sqliteSource, $sqliteDest);
}

// Environment variables untuk runtime Vercel
$envVars = [
    'APP_KEY' => 'base64:2/yOLZxJSc/BNo65v7BOdFiUKr+VWSd7FmhzcaWziVo=',
    'APP_ENV' => 'production',
    'APP_DEBUG' => 'true',
    'DB_CONNECTION' => 'sqlite',
    'DB_DATABASE' => file_exists($sqliteDest) ? $sqliteDest : $sqliteSource,
    'VIEW_COMPILED_PATH' => '/tmp/storage/framework/views',
    'APP_SERVICES_CACHE' => '/tmp/services.php',
    'APP_PACKAGES_CACHE' => '/tmp/packages.php',
    'APP_CONFIG_CACHE' => '/tmp/config.php',
    'APP_ROUTES_CACHE' => '/tmp/routes.php',
    'APP_EVENTS_CACHE' => '/tmp/events.php',
    'CACHE_STORE' => 'array',
    'SESSION_DRIVER' => 'cookie',
    'LOG_CHANNEL' => 'stderr',
];

foreach ($envVars as $key => $val) {
    putenv("{$key}={$val}");
    $_ENV[$key] = $val;
    $_SERVER[$key] = $val;
}

// Teruskan request ke public/index.php Laravel
require __DIR__ . '/../public/index.php';
