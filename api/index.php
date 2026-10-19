<?php

// Vercel runs PHP as a serverless function. Its filesystem is read-only except /tmp,
// so point Laravel's writable paths there unless the environment already sets them.
$defaults = [
    'APP_CONFIG_CACHE' => '/tmp/config.php',
    'APP_EVENTS_CACHE' => '/tmp/events.php',
    'APP_PACKAGES_CACHE' => '/tmp/packages.php',
    'APP_ROUTES_CACHE' => '/tmp/routes.php',
    'APP_SERVICES_CACHE' => '/tmp/services.php',
    'VIEW_COMPILED_PATH' => '/tmp',
    'LOG_CHANNEL' => 'stderr',
    // One line per error, so the message is readable in Vercel's log list.
    'LOG_STDERR_FORMATTER' => Monolog\Formatter\LineFormatter::class,
    'SESSION_DRIVER' => 'cookie',
];

foreach ($defaults as $key => $value) {
    if (getenv($key) === false) {
        putenv("{$key}={$value}");
        $_ENV[$key] = $_SERVER[$key] = $value;
    }
}

// Hand the request to Laravel's normal front controller.
require __DIR__.'/../public/index.php';
