<?php

/** @var \Silex\Application $app */
$app = require_once __DIR__ . '/../bootstrap.php';

$app['debug'] = true;

$app->run();