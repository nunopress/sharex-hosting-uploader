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
$kernel = new MicroKernel('prod', false);
$kernel->loadClassCache();

# Create cache instance
$kernel = new MicroCache($kernel);

# Create request instance
$request = Request::createFromGlobals();

# Setup reverse proxy
Request::enableHttpMethodParameterOverride();
//Request::setTrustedProxies([ '127.0.0.1', $request->server->get('REMOTE_ADDR') ]);
//Request::setTrustedHeaderName(Request::HEADER_FORWARDED, null);

# Give response instance
$response = $kernel->handle($request);
$response->send();

# Bye!
$kernel->terminate($request, $response);
