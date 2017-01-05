<?php

use Symfony\Component\HttpFoundation\Request;

/** @var \Composer\Autoload\ClassLoader $loader */

# Config autoload
$loader = require __DIR__ . '/../app/autoload.php';
include_once __DIR__ . '/../var/bootstrap.php.cache';

# Cache Loader
if (function_exists('apcu_fetch')) {
    $cache_loader = new \Symfony\Component\ClassLoader\ApcClassLoader(sha1(__DIR__), $loader);
    $cache_loader->register();
    $loader->unregister();
}

# Create kernel instance
$kernel = new AppKernel('prod', false);

# Use class cache only when php version is lower of 7.x
if (version_compare(phpversion(), "7.0.0", "<")) {
    $kernel->loadClassCache();
}

# Create cache instance
$kernel = new AppCache($kernel);

# Create request instance
$request = Request::createFromGlobals();

# Give response instance
$response = $kernel->handle($request);
$response->send();

# Bye!
$kernel->terminate($request, $response);
