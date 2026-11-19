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

// Deteksi sertifikat SSL CA untuk koneksi TiDB Cloud
$caFile = __DIR__ . '/../storage/cacert.pem';
if (!file_exists($caFile)) {
    if (file_exists('/etc/pki/tls/certs/ca-bundle.crt')) {
        $caFile = '/etc/pki/tls/certs/ca-bundle.crt';
    } elseif (file_exists('/etc/ssl/certs/ca-certificates.crt')) {
        $caFile = '/etc/ssl/certs/ca-certificates.crt';
    }
}

// Environment variables untuk runtime Vercel (Terhubung ke TiDB Cloud)
$envVars = [
    'APP_KEY' => 'base64:2/yOLZxJSc/BNo65v7BOdFiUKr+VWSd7FmhzcaWziVo=',
    'APP_ENV' => 'production',
    'APP_DEBUG' => 'true',
    'DB_CONNECTION' => 'mysql',
    'DB_HOST' => 'gateway01.ap-southeast-1.prod.aws.tidbcloud.com',
    'DB_PORT' => '4000',
    'DB_DATABASE' => 'db_hukum',
    'DB_USERNAME' => '39JvCyG2eSrZwKE.root',
    'DB_PASSWORD' => 'bEABqRMoNJ9iMkZT',
    'MYSQL_ATTR_SSL_CA' => $caFile,
    'VIEW_COMPILED_PATH' => '/tmp/storage/framework/views',
    'APP_SERVICES_CACHE' => '/tmp/services.php',
    'APP_PACKAGES_CACHE' => '/tmp/packages.php',
    'APP_CONFIG_CACHE' => '/tmp/config.php',
    'APP_ROUTES_CACHE' => '/tmp/routes.php',
    'APP_EVENTS_CACHE' => '/tmp/events.php',
    'CACHE_STORE' => 'array',
    'SESSION_DRIVER' => 'database',
    'SESSION_LIFETIME' => '120',
    'SESSION_SECURE_COOKIE' => 'true',
    'SESSION_SAME_SITE' => 'lax',
    'LOG_CHANNEL' => 'stderr',
];

foreach ($envVars as $key => $val) {
    putenv("{$key}={$val}");
    $_ENV[$key] = $val;
    $_SERVER[$key] = $val;
}

// Paksa HTTPS di lingkungan Vercel agar form submit tidak diblokir browser
$_SERVER['HTTPS'] = 'on';
$_SERVER['SERVER_PORT'] = 443;
if (!isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
    $_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';
}

// Teruskan request ke public/index.php Laravel
require __DIR__ . '/../public/index.php';
