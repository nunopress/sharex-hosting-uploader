<?php

/** @var \Silex\Application $app */
$app = require_once __DIR__ . '/../bootstrap.php';

if (isset($app['http_cache'])) {
    \Symfony\Component\HttpFoundation\Request::setTrustedProxies([ '127.0.0.1', '::1' ]);

    $app['http_cache']->run();
} else {
    $app->run();
}